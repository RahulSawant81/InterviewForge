<?php

namespace App\Services\Question;

use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QuestionService
{
    /**
     * @param array<string, mixed> $filters
     *
     * @return LengthAwarePaginator<int, Question>
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Question::query()
            ->with([
                'category',
                'tags',
            ])
            ->when(
                $filters['category'] ?? null,
                fn ($query, $category) => $query->whereHas(
                    'category',
                    fn ($q) => $q->where('slug', $category)
                )
            )
            ->when(
                $filters['difficulty'] ?? null,
                fn ($query, $difficulty) => $query->where(
                    'difficulty',
                    $difficulty
                )
            )
            ->when(
                $filters['question_type'] ?? null,
                fn ($query, $type) => $query->where(
                    'question_type',
                    $type
                )
            )
            ->when(
                $filters['search'] ?? null,
                fn ($query, $search) => $query->where(
                    'question',
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(10);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Question
    {
        $question = Question::create($data);

        if (! empty($data['tag_ids'])) {
            $question->tags()->sync($data['tag_ids']);
        }

        return $question->load([
            'category',
            'tags',
        ]);
    }

    public function findById(int $id): Question
    {
        return Question::query()
            ->with([
                'category',
                'tags',
            ])
            ->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(
        Question $question,
        array $data
    ): Question {
        $question->update($data);

        if (array_key_exists('tag_ids', $data)) {
            $question->tags()->sync(
                $data['tag_ids'] ?? []
            );
        }

        return $question->load([
            'category',
            'tags',
        ]);
    }

    public function delete(Question $question): void
    {
        $question->delete();
    }

    /**
     * @return Collection<int, Question>
     */
    public function getRandomQuestions(
        string $technology,
        string $difficulty,
        int $limit
    ): Collection {
        return Question::query()
            ->with('category')
            ->whereHas(
                'category',
                fn ($query) => $query->where(
                    'slug',
                    strtolower($technology)
                )
            )
            ->where(
                'difficulty',
                $difficulty
            )
            ->where(
                'is_active',
                true
            )
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
