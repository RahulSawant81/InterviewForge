<?php

namespace Tests\Unit;

use App\Models\Resume;
use App\Models\User;
use App\Services\Resume\ResumeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResumeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_creates_resume_record(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'resume.pdf',
            100,
            'application/pdf'
        );

        $service = app(ResumeService::class);

        $resume = $service->upload(
            $user,
            $file,
            'My Resume'
        );

        $this->assertInstanceOf(
            Resume::class,
            $resume
        );

        $this->assertDatabaseHas('resumes', [
            'id' => $resume->id,
            'title' => 'My Resume',
        ]);
    }

    public function test_upload_stores_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'resume.pdf',
            100,
            'application/pdf'
        );

        $service = app(ResumeService::class);

        $resume = $service->upload(
            $user,
            $file,
            'My Resume'
        );

        Storage::disk('public')->assertExists(
            $resume->file_path
        );
    }

    public function test_list_resumes_returns_user_resumes(): void
    {
        $user = User::factory()->create();

        Resume::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        $service = app(ResumeService::class);

        $resumes = $service->listResumes($user);

        $this->assertCount(2, $resumes);
    }

    public function test_delete_soft_deletes_resume(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'resume.pdf'
        );

        $service = app(ResumeService::class);

        $resume = $service->upload(
            $user,
            $file,
            'Resume'
        );

        $service->delete($resume);

        $this->assertSoftDeleted(
            'resumes',
            [
                'id' => $resume->id,
            ]
        );
    }

    public function test_delete_removes_file_from_storage(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'resume.pdf'
        );

        $service = app(ResumeService::class);

        $resume = $service->upload(
            $user,
            $file,
            'Resume'
        );

        Storage::disk('public')->assertExists(
            $resume->file_path
        );

        $service->delete($resume);

        Storage::disk('public')->assertMissing(
            $resume->file_path
        );
    }
}
