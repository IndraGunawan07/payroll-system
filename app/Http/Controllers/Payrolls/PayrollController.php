<?php

namespace App\Http\Controllers\Payrolls;

use App\Http\Controllers\Controller;
use App\Services\Payrolls\PayrollService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PayrollController extends Controller
{
    public function __construct(
        public PayrollService $payrollService,
    ) {
    }

    public function run (Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payroll_period_id' => ['required', 'string', 'exists:payroll_periods,id'],
            ]);

            $this->payrollService->run($validated);

            return response()->json([
                'message' => 'Payroll success!',
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
