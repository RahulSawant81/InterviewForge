<?php

namespace App\Services\Interview;

use App\Enums\InterviewStatus;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InterviewService
{
    /**
     * Create a new interview for the given user with the provided data.
     *
     *
     * @param array<string, mixed> $data
     */
    public function create(User $user, array $data): Interview
    {

        return Interview::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'difficulty' => $data['difficulty'],
            'technologies' => $data['technologies'],
            'total_questions' => $data['total_questions'],
            'status' => InterviewStatus::DRAFT->value,
        ]);
    }

    /**
     * Get a paginated list of interviews for the given user.
     *
     * @return LengthAwarePaginator<int, Interview>
     */
    public function list(User $user): LengthAwarePaginator
    {
        return Interview::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    /**
     * Find an interview by ID for the given user.
     */
    public function findById(User $user, int $id): Interview
    {
        return Interview::query()
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * Start an interview by setting its status to IN_PROGRESS and recording the start time.
     */
    public function start(Interview $interview): Interview
    {
        $interview->update([
            'status' => InterviewStatus::STARTED->value,
            'started_at' => now(),
        ]);

        return $interview->fresh();
    }

    /**
     * Submit an interview by marking it as completed and storing the completed time.
     *
     * @param array<string, mixed> $data
     */
    public function submit(Interview $interview, array $data): Interview
    {
        $interview->update([
            'status' => InterviewStatus::COMPLETED->value,
            'completed_at' => now(),
        ]);

        return $interview->fresh();
    }
}
