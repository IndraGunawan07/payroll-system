<?php

namespace App\Services\OvertimeRequests;

use App\Models\OvertimeRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class OvertimeRequestService
{
    public function insert(array $validated, User $user): OvertimeRequest
    {
        try {
            return DB::transaction(function () use ($validated, $user) {
                // check if overtime date exist
                $overtimeRequestExist = OvertimeRequest::query()
                ->where('user_id', $user->id)
                ->whereDate('overtime_date', $validated['date'])
                ->exists();
            
                throw_if($overtimeRequestExist, 'Overtime request already exists for selected date');

                return OvertimeRequest::create([
                    'user_id' => $user->id,
                    'overtime_date' => $validated['date'],
                    'hours' => $validated['hours'],
                    'description' => $validated['description'] ?? null,
                    'status' => 'pending',
                ]);
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}