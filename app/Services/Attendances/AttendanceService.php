<?php

namespace App\Services\Attendances;

use App\Models\Attendance;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttendanceService
{
    public function insert(User $user): Attendance
    {
        try {
            return DB::transaction(function () use ($user) {
                $now = now();

                return Attendance::create([
                    'user_id' => $user->id,
                    'check_in_date' => $now->format('Y-m-d'),
                    'check_in_time' => $now,
                ]);
            });
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23505') {
                throw new Exception('You already checked in today');
            }

            throw $exception;
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}