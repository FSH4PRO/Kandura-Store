<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function success($data, string $message = 'Operation completed successfully', int $code = 200)
    {
        return response()->json([
            'code' => $code,
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }

    public function failed(string $message = 'Failed', $data = null, int $code = 400)
    {
        return response()->json([
            'code' => $code,
            'success'   => false,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }
}
