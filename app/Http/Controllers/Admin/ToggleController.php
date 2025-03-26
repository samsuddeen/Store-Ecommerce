<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ToggleController extends Controller
{
    private $tables = [
        'customers',
        'users',
        'products'
    ];

    public function toggleStatus(Request $request)
    {
        if (!in_array($request->table, $this->tables)) {
            throw ValidationException::withMessages(['table', 'invalid table name']);
        }
        $request->validate(
            [
                'table' => ['required'],
                'id' => ['required']
            ]
        );

        try {
            $test =  DB::table($request->table)->find($request->id);
            DB::table($request->table)
                ->where('id', $request->id)
                ->update(
                    [
                        'status' => !$test->status
                    ]
                );

            return response()->json(['status toggled']);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
