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
        $filters = [
            'search'          => $request->query('search'),
            'city_id'         => $request->query('city_id'),
            'has_coordinates' => $request->query('has_coordinates'),
            'per_page'        => $request->query('per_page'),
            'sort_by'         => $request->query('sort_by'),
            'sort_dir'        => $request->query('sort_dir'),
        ];

        $addresses = $this->service->listForUser(
            $request->user()->id,
            $filters
        );

        // نخلي الـ pagination كما هو (data, meta, links) داخل data تبع success
        return $this->success(
            AddressResource::collection($addresses)->response()->getData(),
            'addresses retrieved successfully'
        );
    }

    public function store(StoreAddressRequest $request)
    {
        $address = $this->service->create(
            $request->validated(),
            auth()->user()->id
        );

        return $this->success(new AddressResource($address), 'address created successfully', 201);
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $this->authorize('update', $address);

        $address = $this->service->update($address, $request->validated());

        return $this->success(new AddressResource($address), 'address updated successfully');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        $this->service->delete($address);

        return $this->success(null, 'address deleted successfully');
    }
}
