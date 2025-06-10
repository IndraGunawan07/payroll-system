<?php

namespace App\Http\Controllers\OvertimeRequests;

use App\Http\Controllers\Controller;
use App\Services\OvertimeRequests\OvertimeRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OvertimeRequestController extends Controller
{
    public function __construct(
        public OvertimeRequestService $overtimeRequestService,
    ) {  
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'date' => ['required', 'date'],
                'hours' => ['required', 'numeric', 'max:3'],
                'description' => ['nullable', 'string'],
            ]);

            return response()->json([
                'message' => 'Data created successfully',
                'data' => $this->overtimeRequestService->insert($validated, $request->user()),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
