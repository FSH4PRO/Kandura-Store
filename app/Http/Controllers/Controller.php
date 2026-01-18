<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function success($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'code' => $code,
            'success'   => true,
            'message'   => $message ?: __('messages.success_operation'),
            'data'      => $data,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }

    public function failed(string $message = null, $data = null, int $code = 400)
    {
        return response()->json([
            'code' => $code,
            'success'   => false,
            'message'   => $message ?: __('messages.failed_operation'),
            'data'      => $data,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }
}
