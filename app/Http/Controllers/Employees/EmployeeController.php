<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EmployeeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'paginate.page' => ['required', 'string', 'min:1'],
                'paginate.size' => ['required', 'string', 'min:1'],
            ]);

            $employees = User::query()
                ->where('is_admin', false)
                ->paginate(
                    perPage: $validated['paginate']['size'],
                    page: $validated['paginate']['page'],
                );

            return response()->json([
                'message' => 'Data retrieved succesfully',
                'data' => $employees,
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
