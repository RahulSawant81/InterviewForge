<?php

namespace Tests\Unit;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use App\Models\Interview;
use App\Models\User;
use App\Services\Interview\InterviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_interview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $service = app(InterviewService::class);

        $interview = $service->create($user, [
            'title' => 'Laravel Interview',
            'type' => InterviewType::MOCK,
            'difficulty' => DifficultyLevel::INTERMEDIATE,
            'technologies' => ['PHP', 'Laravel'],
            'total_questions' => 10,
        ]);

        $this->assertInstanceOf(
            Interview::class,
            $interview
        );

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'title' => 'Laravel Interview',
        ]);
    }

    public function test_list_interviews_returns_user_interviews(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Interview::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
            ]);

        $service = app(InterviewService::class);

        $result = $service->list($user);

        $this->assertCount(
            3,
            $result->items()
        );
    }

    public function test_find_interview_by_id(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        $service = app(InterviewService::class);

        $result = $service->findById(
            $user,
            $interview->id
        );

        $this->assertEquals(
            $interview->id,
            $result->id
        );
    }

    public function test_start_interview(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'status' => InterviewStatus::DRAFT,
        ]);

        $service = app(InterviewService::class);

        $result = $service->start(
            $interview
        );

        $this->assertEquals(
            InterviewStatus::STARTED,
            $result->status
        );

        $this->assertNotNull(
            $result->started_at
        );
    }

    public function test_submit_interview(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'status' => InterviewStatus::STARTED,
        ]);

        $service = app(InterviewService::class);

        $result = $service->submit(
            $interview,
            []
        );

        $this->assertEquals(
            InterviewStatus::COMPLETED,
            $result->status
        );

        $this->assertNotNull(
            $result->completed_at
        );
    }
}
