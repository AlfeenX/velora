<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Address;

use App\Http\Controllers\Controller;

use App\Http\Requests\User\StoreAddressRequest;

use App\Http\Resources\User\AddressResource;

class AddressController extends Controller
{
    public function index()
    {
        return AddressResource::collection(
            auth()->user()->addresses
        );
    }

    public function store(
        StoreAddressRequest $request
    ) {

        $address = auth()->user()
            ->addresses()
            ->create(
                $request->validated()
            );

        return new AddressResource($address);
    }
}