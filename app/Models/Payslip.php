<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payslip extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'payroll_period_id',
        'base_salary',
        'total_attendance_days',
        'salary',
        'total_overtime_hours',
        'overtime_pay',
        'total_reimbursements',
        'take_home_pay',
        'created_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function payslipDetails(): HasMany
    {
        return $this->hasMany(PayslipDetail::class);
    }
}
