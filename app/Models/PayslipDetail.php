<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayslipDetail extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'payslip_id',
        'type',
        'amount',
        'formula',
    ];

    public function payslip(): BelongsTo
    {
        return $this->belongsTo(Payslip::class);
    }
}
