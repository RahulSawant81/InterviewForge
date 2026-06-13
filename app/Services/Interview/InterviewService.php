<?php

namespace App\Services\Interview;

use App\Enums\InterviewStatus;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InterviewService
{
    public function create(User $user, array $data): Interview {

        return Interview::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'type' => $data['type'],
            'difficulty' => $data['difficulty'],
            'technologies' => $data['technologies'],
            'total_questions' => $data['total_questions'],
            'status' => InterviewStatus::DRAFT,
        ]);
    }

    public function list(User $user): LengthAwarePaginator
    {
        return Interview::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }
}
