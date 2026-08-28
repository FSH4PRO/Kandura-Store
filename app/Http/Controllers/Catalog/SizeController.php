<?php

namespace App\Http\Controllers\Catalog;

use App\Models\Size;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;

class SizeController extends Controller
{
    /**
     * GET /api/sizes — doc §11/§20 gap #2: previously the frontend had no
     * way to fetch the real size catalog and had to fall back to
     * hardcoded placeholder ids. Public/reference data (no ownership,
     * nothing sensitive), so it's intentionally not behind auth:customer.
     */
    public function index()
    {
        $sizes = Size::query()->ordered()->get();

        return $this->success(
            SizeResource::collection($sizes),
            __('messages.success_operation')
        );
    }
}
