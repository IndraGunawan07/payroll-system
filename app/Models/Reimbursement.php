<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'payroll_period_id',
        'request_date',
        'amount',
        'description',
        'status',
        'is_payroll',
    ];
}
