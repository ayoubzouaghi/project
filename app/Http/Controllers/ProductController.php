<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Ressources\Product;


class ProductController extends Controller
{
    public function create(ProductRequest $request)
    {
        $user = User::find(Auth::id());
        $product = new Products();
        if ($user) {
            $product->fill(['user_id' => Auth::user()->id]);
            $product->fill(['name' => $request->name]);
            $product->fill(['price' => $request->price]);
            $product->fill(['color' => $request->color]);
            $product->save();
            return response()->json(['message' => 'product created', 'success' => 1, 'status' => 200]);
        }
    }


    public function show($id)
    {
        $product = Products::find($id);
        if ($product) {
            $product = new ResourcesProduct($product);
            return response()->json(['message' => 'product', 'success' => 1, 'status' => 200, "product" => $product]);
        } else {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        }
    }
    public function UserProduct($id)
    {
        $products  = Products::where('user_id', $id)->get();

        return response()->json(['message' => "All user products", 'success' => 1, 'status' => 200, 'product' => ResourcesProduct::collection($products)]);
    }

    public function update(ProductRequest $request, $id)
    {
        $user = User::find(Auth::id());
        $product = Products::find($id);


        if ($product->user_id == $user->id) {
            $product->update($request->only('name', 'price', 'color'));
            return response()->json(['message' => "product updated", 'success' => 1, 'status' => 200, 'product' => $product]);
        } else {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        }
    }
}
