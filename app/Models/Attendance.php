<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'payroll_period_id',
        'check_in_date',
        'check_in_time',
        'is_payroll,'
    ];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
