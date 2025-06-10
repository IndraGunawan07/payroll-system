<?php

namespace App\Http\Controllers\Attendances;

use App\Http\Controllers\Controller;
use App\Services\Attendances\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AttendanceController extends Controller
{
    public function __construct(
        public AttendanceService $attendanceService,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            throw_if(now()->isWeekend(), 'Can not submit attendance on weekend');

            return response()->json([
                'message' => 'Data created successfully!',
                'data' => $this->attendanceService->insert($request->user()),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
