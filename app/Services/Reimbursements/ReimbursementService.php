<?php

namespace App\Services\Reimbursements;

use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReimbursementService
{
    public function insert(array $validated, User $user): Reimbursement
    {
        try {
            return DB::transaction(function () use ($validated, $user) {
                return Reimbursement::create([
                    'user_id' => $user->id,
                    'request_date' => $validated['date'],
                    'amount' => $validated['amount'],
                    'description' => $validated['description'] ?? null,
                    'status' => 'pending',
                ]);
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}