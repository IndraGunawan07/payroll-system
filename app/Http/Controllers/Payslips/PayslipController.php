<?php

namespace App\Http\Controllers\Payslips;

use App\Http\Controllers\Controller;
use App\Services\Payslips\PayslipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PayslipController extends Controller
{
    public function __construct(
        public PayslipService $payslipService,
    ){
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payroll_period_id' => ['required', 'string', 'exists:payroll_periods,id'],
            ]);

            $this->payslipService->generate($validated, $request->user());

            return response()->json([
                'message' => 'Payslip generated successfully!',
            ]);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function summary(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'paginate.page' => ['required', 'numeric', 'min:1'],
                'paginate.size' => ['required', 'numeric', 'min:1'],
            ]);

            $user = $request->user();

            // throw_if(!$user->is_admin, 'Non admin can not access this menu!');

            $data = $this->payslipService->summary($validated);
            
            return response()->json([
                'message' => 'Data retrieved successfully!',
                'data' => $data,
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
