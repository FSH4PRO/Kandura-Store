<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DesignOptionIndexRequest;
use App\Http\Requests\Admin\StoreDesignOptionRequest;
use App\Http\Requests\Admin\UpdateDesignOptionRequest;
use App\Models\DesignOption;
use App\Services\Admin\DesignOptionService;

class DesignOptionController extends Controller
{
    public function __construct(
        protected DesignOptionService $service
    ) {}

    public function index(DesignOptionIndexRequest $request)
    {
        $filters = $request->validated();

        $options = $this->service->list($filters);


        $types = [
            'color',
            'dome_type',
            'fabric_type',
            'sleeve_type',
        ];

        return view('content.design-options.index', [
            'options' => $options,
            'filters' => $filters,
            'types'   => $types,
        ]);
    }

    public function create()
    {
        $types = [
            'color',
            'dome_type',
            'fabric_type',
            'sleeve_type',
        ];

        return view('content.design-options.create', compact('types'));
    }

    public function store(StoreDesignOptionRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.design-options.index')
            ->with('success', __('design_options.messages.created'));
    }

    public function edit(DesignOption $designOption)
    {
        $types = [
            'color',
            'dome_type',
            'fabric_type',
            'sleeve_type',
        ];

        return view('content.design-options.edit', [
            'designOption' => $designOption,
            'types'        => $types,
        ]);
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        $this->service->update($designOption, $request->validated());

        return redirect()
            ->route('admin.design-options.index')
            ->with('success', __('design_options.messages.updated'));
    }

    public function destroy(DesignOption $designOption)
    {
        $this->service->delete($designOption);

        return redirect()
            ->route('admin.design-options.index')
            ->with('success', __('design_options.messages.deleted'));
    }
}
