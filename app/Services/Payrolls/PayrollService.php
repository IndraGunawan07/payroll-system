<?php

namespace App\Services\Payrolls;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\PayrollPeriod;
use App\Models\Reimbursement;
use Illuminate\Support\Facades\DB;
use Throwable;

class PayrollService
{
    public function run(array $validated): void
    {
        try {
            DB::transaction(function () use ($validated) {
                $payrollPeriod = PayrollPeriod::query()
                    ->find($validated['payroll_period_id']);

                throw_if($payrollPeriod->is_payroll, 'Payroll already done for this period!');

                $startDate = $payrollPeriod->start_date;
                $endDate = $payrollPeriod->end_date;

                Attendance::query()
                    ->whereDate('check_in_date', '>=', $startDate)
                    ->whereDate('check_in_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('is_payroll', false)
                    ->update([
                        'is_payroll' => true,
                        'payroll_period_id' => $payrollPeriod->id,
                    ]);

                OvertimeRequest::query()
                    ->whereDate('overtime_date', '>=', $startDate)
                    ->whereDate('overtime_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('is_payroll', false)
                    ->update([
                        'is_payroll' => true,
                        'payroll_period_id' => $payrollPeriod->id,
                    ]);

                Reimbursement::query()
                    ->whereDate('request_date', '>=', $startDate)
                    ->whereDate('request_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('is_payroll', false)
                    ->update([
                        'is_payroll' => true,
                        'payroll_period_id' => $payrollPeriod->id,
                    ]);

                // update payroll period
                $payrollPeriod->update([
                    'is_payroll' => true,
                ]);
            });

        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}