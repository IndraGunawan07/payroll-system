<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollPeriod extends Model
{
    use HasUuids;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_working_days',
        'processed_at',
        'is_payroll',
        'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
