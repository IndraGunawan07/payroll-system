<?php

namespace App\Services\PayrollPeriods;

use App\Models\PayrollPeriod;
use App\Models\User;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exact;
use Throwable;

class PayrollPeriodService
{
    public function list(array $validated): LengthAwarePaginator
    {
        return PayrollPeriod::query()
            ->paginate(
                perPage: $validated['paginate']['size'],
                page: $validated['paginate']['page'],
            );
    }

    public function insert(array $validated, User $user): PayrollPeriod
    {
        try {
            return DB::transaction(function () use ($validated, $user) {
                // calculate working days
                $startDate = $validated['start_date'];
                $endDate = $validated['end_date'];

                $period = CarbonPeriod::create($startDate, $endDate);
                $workingDays = 0;

                foreach ($period as $date) {
                    if ($date->isWeekday()) {
                        $workingDays++;
                    }
                }

                $payrollPeriod = PayrollPeriod::create([
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_working_days' => $workingDays,
                    'created_by' => $user->id,
                ]);

                return $payrollPeriod;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}