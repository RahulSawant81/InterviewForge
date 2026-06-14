<?php

namespace App\Services\Resume;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function upload(User $user, UploadedFile $file, string $title): Resume
    {
        // Generate a unique filename for storage
        $storedFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store the file in the 'resumes' directory
        $path = $file->storeAs('resumes', $storedFilename, 'public');

        // Create a new Resume record in the database
        return Resume::create([
            'user_id' => $user->id,
            'title' => $title,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $storedFilename,
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    public function listResumes(User $user): Collection
    {
        return Resume::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function delete(Resume $resume): void
    {
        if (
            $resume->file_path &&
            Storage::disk('public')->exists($resume->file_path)
        ) {
            Storage::disk('public')->delete(
                $resume->file_path
            );
        }

        $resume->delete();
    }

    public function download(Resume $resume): StreamedResponse
    {
        if (
            $resume->file_path &&
            Storage::disk('public')->exists($resume->file_path)
        ) {
            return Storage::disk('public')->download(
                $resume->file_path,
                $resume->original_filename
            );
        }

        abort(404, 'File not found');
    }
}
