<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Http\Requests\AddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    public function store(AddressRequest $request)
    {
        $address = new Address;
        $address->user_id = auth()->id();
        $address->name = $request->name;
        $address->street = $request->street;
        $address->details = $request->details;
        $address->country = $request->country;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->mobile = $request->mobile;
        $address->save();
        return response()->json(['address' => $address->id]);
    }

    public function show($id)
    {
        $address = Address::findOrFail($id);
        return response()->json(new AddressResource($address));
    }

    public function update(AddressRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
