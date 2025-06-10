<?php

namespace App\Http\Controllers\PayrollPeriods;

use App\Http\Controllers\Controller;
use App\Services\PayrollPeriods\PayrollPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PayrollPeriodController extends Controller
{
    public function __construct(
        public PayrollPeriodService $payrollPeriodService,        
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'paginate.size' => ['required', 'numeric', 'min:1'],
                'paginate.page' => ['required', 'numeric', 'min:1'],
            ]);

            $payrollPeriods = $this->payrollPeriodService->list($validated);

            return response()->json([
                'message' => 'Data successfully retrieved!',
                'data' => $payrollPeriods,
            ]);
        } catch(Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date'],
            ]);

            return response()->json([
                'message' => 'Data created successfully',
                'data' => $this->payrollPeriodService->insert($validated, $request->user()),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
