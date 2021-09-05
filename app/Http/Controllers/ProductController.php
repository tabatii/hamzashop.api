<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Requests\QuickEditRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['show']);
    }

    public function index()
    {
        $products = Product::latest()->get();
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = new Product;
        $product->long_title = $request->long;
        $product->short_title = $request->short;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->images = $request->images;
        $product->features = $request->features;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();
        return response()->json(['product' => $product->id]);
    }

    public function show($id)
    {
        $product = Product::where('status', 'public')->findOrFail($id);
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->long_title = $request->long;
        $product->short_title = $request->short;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->images = $request->images;
        $product->features = $request->features;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();
        return response()->json(['product' => $product->id]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json();
    }

    public function quick(QuickEditRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();
        return response()->json();
    }

    public function display($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'public';
        $product->save();
        return response()->json();
    }

    public function hide($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'private';
        $product->save();
        return response()->json();
    }
}
