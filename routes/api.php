<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResumeAnalysisController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\ResumeReportController;
use App\Models\Interview;
use App\Services\AI\GeminiService;
use App\Services\Interview\InterviewReportService;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public authentication endpoints.
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        // Protected endpoints require a valid Sanctum token.
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // Profile CRUD for the currently authenticated user.
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);
            Route::patch('/', [ProfileController::class, 'update']);
            // Route::post('/', [ProfileController::class, 'update']);
            Route::delete('/', [ProfileController::class, 'destroy']);
        });

        Route::prefix('resumes')->group(function () {
            Route::get('/', [ResumeController::class, 'index']);
            Route::post('/', [ResumeController::class, 'store']);

            Route::get('/{resume}', [ResumeController::class, 'show']);
            Route::get('/{resume}/analysis', [ResumeAnalysisController::class, 'show']);
            Route::post('/{resume}/analysis', [ResumeAnalysisController::class, 'store']);
            Route::get('/{resume}/download', [ResumeController::class, 'download']);

            Route::delete('/{resume}', [ResumeController::class, 'destroy']);
            Route::get('/{resume}/report', ResumeReportController::class);
        });

        Route::prefix('interviews')->group(function () {
            Route::get('/', [InterviewController::class, 'index']);
            Route::post('/', [InterviewController::class, 'store']);
            Route::get('/{interview}', [InterviewController::class, 'show']);
            Route::post('/{interview}/start', [InterviewController::class, 'start']);
            Route::post('/{interview}/submit', [InterviewController::class, 'submit']);
            Route::get('/{interview}/report', [InterviewController::class, 'report']);
            Route::post('/{interview}/generate-questions', [InterviewController::class, 'generateQuestions']);
            Route::post('/{interview}/answers', [InterviewController::class, 'submitAnswers']);
            Route::get('/{interview}/answers', [InterviewController::class, 'getAnswers']);
            Route::get('/{interview}/questions', [InterviewController::class, 'questions']);
        });
        Route::post('/questions/{question}/answer', [InterviewController::class, 'answer']);
        Route::apiResource('questions', QuestionController::class);

        // Admin-only route example using role middleware.
        Route::get('/admin-only', function () {
            return response()->json([
                'message' => 'Admin access granted.',
            ]);
        })->middleware('role:admin|super_admin');
    });

    // Route::get('/gemini-evaluation-test', function (
    //     GeminiService $service
    // ) {

    //     return $service->evaluateInterview(
    //         'mock',
    //         [
    //             'What is Dependency Injection?',
    //         ],
    //         [
    //             'Dependency Injection is a design pattern used to provide dependencies.'
    //         ]
    //     );
    // });

    // Route::get('/gemini-test', function () {

    //     $response = Gemini::generativeModel(
    //         model: 'gemini-2.5-flash'
    //     )->generateContent(
    //         'Say hello from Gemini'
    //     );

    //     return response()->json([
    //         'response' => $response->text(),
    //     ]);
    // });

    Route::get(
        '/report-test/{interview}',
        function (
            Interview $interview,
            InterviewReportService $service
        ) {
            return $service->generateReport(
                $interview
            );
        }
    );
});
