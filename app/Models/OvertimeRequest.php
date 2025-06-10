<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'payroll_period_id',
        'overtime_date',
        'hours',
        'description',
        'status',
        'is_payroll',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
