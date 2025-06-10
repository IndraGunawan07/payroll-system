<?php

namespace App\Http\Controllers\Reimbursements;

use App\Http\Controllers\Controller;
use App\Services\Reimbursements\ReimbursementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ReimbursementController extends Controller
{
    public function __construct(
        public ReimbursementService $reimbursementService,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'date' => ['required', 'date'],
                'amount' => ['required', 'numeric', 'min:1'],
                'description' => ['nullable', 'string'],
            ]);

            return response()->json([
                'message' => 'Data created successfully!',
                'data' => $this->reimbursementService->insert($validated, $request->user()),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
