<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DesignIndexRequest; 
use App\Models\Design;
use App\Models\Size;
use App\Models\DesignOption;
use App\Services\Admin\DesignService;

class DesignController extends Controller
{
    public function __construct(
        protected DesignService $service
    ) {}

    public function index(DesignIndexRequest $request)
    {
        $filters = $request->validated();

        $designs = $this->service->list($filters);

        $sizes   = Size::orderBy('sort_order')->get();
        $options = DesignOption::where('is_active', true)->get();

        return view('content.designs.index', [
            'designs' => $designs,
            'filters' => $filters,
            'sizes'   => $sizes,
            'options' => $options,
        ]);
    }

    public function show(Design $design)
    {
        $design->load(['sizes', 'options', 'customer.user', 'media']);

        return view('content.designs.show', [
            'design' => $design,
        ]);
    }
}
