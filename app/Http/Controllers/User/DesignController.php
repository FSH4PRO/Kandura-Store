<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Design\DesignIndexRequest;
use App\Http\Requests\Design\StoreDesignRequest;
use App\Http\Requests\Design\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Services\User\DesignService;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    protected DesignService $service;

    public function __construct(DesignService $service)
    {
        $this->service = $service;
    }

    
    public function index(DesignIndexRequest $request)
    {
        $customer = auth('customer')->user();

        $filters = $request->validated();

        $designs = $this->service->listForUser($customer, $filters);

        return $this->success(
            DesignResource::collection($designs)->response()->getData(),
            'Designs retrieved successfully'
        );
    }

   
    public function store(StoreDesignRequest $request)
    {
        $customer = auth('customer')->user();

        $design = $this->service->create($request->validated(), $customer);

        return $this->success(
            new DesignResource($design),
            __('messages.design_created'),
            201
        );
    }

    
    public function show(Design $design)
    {
        $this->authorize('view', $design);

        return $this->success(
            new DesignResource($design->load(['sizes', 'options', 'customer.user'])),
            'Design details'
        );
    }

        public function update(UpdateDesignRequest $request, Design $design)
    {
        $this->authorize('update', $design);

        $design = $this->service->update($design, $request->validated());

        return $this->success(
            new DesignResource($design),
            __('messages.design_updated')
        );
    }

    
    public function destroy(Design $design)
    {
        $this->authorize('delete', $design);

        $this->service->delete($design);

        return $this->success(null, __('messages.design_deleted'));
    }
}
