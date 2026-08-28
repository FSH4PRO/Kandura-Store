<?php

namespace App\Http\Controllers\Catalog;

use Illuminate\Http\Request;
use App\Models\DesignOption;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignOptionResource;

class DesignOptionController extends Controller
{
    /**
     * GET /api/design-options[?type=color] — doc §11/§20 gap #2. Only
     * active options are returned by default, matching existing business
     * logic (DesignOption::scopeActive()) — inactive options were never
     * meant to be selectable when building a new design.
     */
    public function index(Request $request)
    {
        $options = DesignOption::query()
            ->active()
            ->type($request->query('type'))
            ->orderBy('type')
            ->get();

        return $this->success(
            DesignOptionResource::collection($options),
            __('messages.success_operation')
        );
    }
}
