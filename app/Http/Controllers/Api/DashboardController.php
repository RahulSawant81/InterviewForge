<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\Interview;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $user = auth()->user();

        $data = [
            'resume_count' => Resume::where(
                'user_id',
                $user->id
            )->count(),

            'interview_count' => Interview::where(
                'user_id',
                $user->id
            )->count(),

            'report_count' => 0,
        ];

        // if (class_exists(\App\Models\Report::class)) {

        //     $data['report_count'] =
        //         \App\Models\Report::where(
        //             'user_id',
        //             $user->id
        //         )->count();

        // } else {

        //     $data['report_count'] = 0;
        // }

        return response()->json([
            'status' => true,
            'message' => 'Dashboard stats fetched successfully',
            'data' => $data,
        ]);
    }
}
