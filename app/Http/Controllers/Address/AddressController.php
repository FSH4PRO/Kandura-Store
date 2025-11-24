<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressIndexRequest;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\Address\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected AddressService $service;

    public function __construct(AddressService $service)
    {
        $this->service = $service;
    }

    public function index(AddressIndexRequest $request)
    {
        
        $filters = $request->filters();

        $customer = auth('customer')->user();

        $addresses = $this->service->listForUser(
            $customer->id,
            $filters
        );

        return $this->success(
            AddressResource::collection($addresses)->response()->getData(),
             __('messages.address_list')
        );
    }

    public function store(StoreAddressRequest $request)
    {
        $customer = auth('customer')->user();

        $address = $this->service->create(
            $request->validated(),
            $customer->id
        );

        return $this->success(new AddressResource($address),  __('messages.address_created'), 201);
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $this->authorize('update', $address);

        $address = $this->service->update($address, $request->validated());

        return $this->success(new AddressResource($address), __('messages.address_updated'));
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        $this->service->delete($address);

        return $this->success(null, __('messages.address_deleted'));
    }
}
