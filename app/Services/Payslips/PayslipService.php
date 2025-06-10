<?php

namespace App\Services\Payslips;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\PayrollPeriod;
use App\Models\Payslip;
use App\Models\PayslipDetail;
use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;

class PayslipService
{
    public function generate(array $validated, User $user): void
    {
        try {
            DB::transaction(function () use ($validated, $user) {
                $payslipData = [];
                $now = now();

                // get payroll period
                $payrollPeriod = PayrollPeriod::query()
                    ->where('is_payroll', true)
                    ->find($validated['payroll_period_id']);

                throw_if(!$payrollPeriod, 'Payroll has not been done for this period!');

                $startDate = $payrollPeriod->start_date;
                $endDate = $payrollPeriod->end_date;

                $workingDays = $payrollPeriod->total_working_days;

                // ATTENDANCE
                $attendancesBaseQuery = Attendance::query()
                    ->whereDate('check_in_date', '>=', $startDate)
                    ->whereDate('check_in_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('user_id', $user->id)
                    ->where('is_payroll', true);

                $attendancesCount = $attendancesBaseQuery
                    ->clone()
                    ->count();

                if ($attendancesCount > 0) {
                    $payslipData['attendance'] = $attendancesCount;
                }

                // OVERTIME
                $overtimeBaseQuery = OvertimeRequest::query()
                    ->whereDate('overtime_date', '>=', $startDate)
                    ->whereDate('overtime_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('user_id', $user->id)
                    ->where('is_payroll', true);

                $overtimeRequests = $overtimeBaseQuery
                    ->clone()
                    ->get();

                // REIMBURSEMENT
                $reimbursementBaseQuery = Reimbursement::query()
                    ->whereDate('request_date', '>=', $startDate)
                    ->whereDate('request_date', '<=', $endDate)
                    ->whereNull('processed_at')
                    ->where('user_id', $user->id)
                    ->where('is_payroll', true);

                $reimbursements = $reimbursementBaseQuery
                    ->clone()
                    ->get();

                throw_if(empty($payslipData), 'No available data to process!');

                $baseSalary = $user->base_salary;

                $prorateSalary = round($baseSalary / $workingDays, 2);
                $attendanceDay = $payslipData['attendance'] ?? 0;
                $attendanceSalary = $workingDays === $attendanceDay ? $baseSalary : $prorateSalary * $attendanceDay;

                $overtime = $overtimeRequests->sum('hours') ?? 0;
                $overtimePay = 2 * $prorateSalary * $overtime;
                $reimbursement = $reimbursements->sum('amount') ?? 0;

                $takeHomePay = $attendanceSalary + $overtimePay - $reimbursement;

                $payslip = Payslip::create([
                    'user_id' => $user->id,
                    'payroll_period_id' => $payrollPeriod->id,
                    'base_salary' => $baseSalary,
                    'total_attendance_days' => $attendanceDay,
                    'salary' => $attendanceSalary,
                    'total_overtime_hours' => $overtime,
                    'overtime_pay' => $overtimePay,
                    'total_reimbursements' => $reimbursement,
                    'take_home_pay' => $takeHomePay,
                    'created_by' => $user->id,
                ]);

                if ($attendanceSalary > 0) {
                    if ($attendanceDay === $workingDays) {
                        $formula = 'Full working days, full salary is ' . $baseSalary;
                    } else {
                        $formula = $attendanceDay . ' working days, prorate salary is ' . $prorateSalary . '(' . $attendanceDay . ' * ' . $prorateSalary . ' = ' . $attendanceSalary . ')'; 
                    }
                    $this->createPayslipDetail('attendance', $payslip->id, $attendanceSalary, $formula);
                }

                if ($overtime > 0) {
                    $formula = 'Overtime hours (' . $overtime . ') * 2 * ' . $prorateSalary . ' (prorate salary)';
                    $this->createPayslipDetail('overtime', $payslip->id, $attendanceSalary, $formula);
                }

                if ($reimbursement > 0) {
                    $formula = 'Reimbursement total ' . $reimbursement;
                    $this->createPayslipDetail('reimbursement', $payslip->id, $attendanceSalary, $formula);
                }


                $updateProcessedAt = ['processed_at' => $now];

                // update processed at
                $attendancesBaseQuery->update($updateProcessedAt);
                $overtimeBaseQuery->update($updateProcessedAt);
                $reimbursementBaseQuery->update($updateProcessedAt);
                $payrollPeriod->update($updateProcessedAt);
            });
        } catch (Throwable $exception) {
            Log::error('Error while generate payslip', [
                'message' => $exception->getMessage(),
                'server_time' => now(),
                'user_id' => $user->id,
                'payload' => $validated,
            ]);

            throw $exception;
        }
    }

    private function createPayslipDetail(
        string $type,
        string $payslipId, 
        int|float $amount,
        string $formula,
    ) {
        PayslipDetail::create([
            'payslip_id' => $payslipId,
            'type' => $type,
            'amount' => $amount,
            'formula' => $formula,
        ]);
    }

    public function summary(array $validated): array
    {
        try {
            $payslips = Payslip::query()
                ->with([
                    'user',
                ])
                ->paginate(
                    perPage: $validated['paginate']['size'],
                    page: $validated['paginate']['page'],
                )
                ->through(function (Payslip $payslip) {
                    return [
                        'id' => $payslip->id,
                        'name' => $payslip->user->username,
                        'take_home_pay' => $payslip->take_home_pay,
                    ];
                });

            return [
                'data' => $payslips,
                'total' => Payslip::query()
                    ->sum('take_home_pay') ?? 0,
            ];
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}