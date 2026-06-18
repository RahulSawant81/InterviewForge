<?php

namespace Tests\Feature\Resume;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResumeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_resume(): void
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resumes', [
            'title' => 'Senior PHP Resume',
            'resume' => UploadedFile::fake()->create(
                'resume.pdf',
                100,
                'application/pdf'
            ),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath(
                'message',
                'Resume uploaded successfully'
            );

        $this->assertDatabaseCount(
            'resumes',
            1
        );
    }

    public function test_authenticated_user_can_list_resumes(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Resume::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/resumes'
        );

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_resume(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resume $resume */
        $resume = Resume::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/resumes/{$resume->id}"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'data.id',
                $resume->id
            );
    }

    public function test_authenticated_user_can_delete_resume(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resume $resume */
        $resume = Resume::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson(
            "/api/v1/resumes/{$resume->id}"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Resume deleted successfully'
            );
    }

    public function test_guest_cannot_access_resumes(): void
    {
        $response = $this->getJson(
            '/api/v1/resumes'
        );

        $response->assertStatus(401);
    }
}
