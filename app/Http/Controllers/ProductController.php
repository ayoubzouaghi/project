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
use App\Http\Requests\AdminRequest;


class ProductController extends Controller
{
    public function create(ProductRequest $request)
    {
        $user = User::find(Auth::id());
        if ($user) {
            $product = new Products();
            $product->fill([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'price' => $request->price,
                'color' => $request->color,
                'image' => $request->image
            ]);
            $product->save();
            return response()->json(['message' => 'product created', 'success' => 1, 'status' => 200]);
        } else {
            return response()->json(['message' => 'user not found', 'success' => -1, 'status' => 400]);
        }
    }


    public function show($id)
    {
        $product = Products::where(['user_id' => Auth::id(), 'id' => $id])->first();
        if ($product) {
            $product = new ResourcesProduct($product);
            return response()->json(['message' => 'product', 'success' => 1, 'status' => 200, "product" => $product]);
        } else {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        }
    }
    public function UserProduct()
    {
        $products  = Products::where('user_id', Auth::id())->get();

        return response()->json(['message' => "All user products", 'success' => 1, 'status' => 200, 'product' => ResourcesProduct::collection($products)]);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Products::where(['user_id' => Auth::id(), 'id' => $id])->first();

        if ($product) {
            $product->update($request->only('name', 'price', 'color', 'image'));
            return response()->json(['message' => "product updated", 'success' => 1, 'status' => 200, 'product' => $product]);
        } else {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        }
    }


    public function UpdateProductImage(Request $request, $id)
    {

        $product = Products::where(['user_id' => Auth::id(), 'id' => $id])->first();
        if ($product) {
            $base64_str = preg_replace('/^data:image\/\w+;base64,/', '', $request->image);
            $image = base64_decode($base64_str);
            $type = explode(';', $request->image)[0];
            $type = explode('/', $type)[1]; // png or jpg etc
            $alea = time();
            $url = public_path('/products/') . $alea . $product->id . '.' . $type;
            file_put_contents($url, $image);
            $product->image = $alea . $product->id . '.' . $type;
            $product->save();
            return response()->json(['message' => "product image changed successfully", 'succes' => 1, 'status' => 200]);
        } else {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        }
    }

    public function GetProductImage(Request $request, $id)
    {
        $product = Products::where(['user_id' => Auth::id(), 'id' => $id])->first();
        if ($product) {
            return response()->json(['message' => "product Image", 'success' => 1, 'status' => 200, 'image' => $product->image]);
        } else {
            return response()->json(['message' => 'product  not found', 'success' => -1, 'status' => 400]);
        }
    }

    public function getAllProduct(AdminRequest $request)
    {
        $products = Products::get();
        return response()->json(['message' => "All  products", 'success' => 1, 'status' => 200, 'product' => ResourcesProduct::collection($products)]);
    }

    public function deleteProduct(AdminRequest $request, $id)
    {
        $product = Products::find($id);
        if (!$product) {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        } else {
            $product->delete();
            return response()->json((['message' => 'Product deleted', 'success' => 1, 'status' => 200,]));
        }
    }

    public function userDeleteProduct(Request $request, $id)
    {
        $product = Products::where(['user_id' => Auth::id(), 'id' => $id])->first();
        if (!$product) {
            return response()->json(['message' => 'product not found', 'success' => -1, 'status' => 400]);
        } else {
            $product->delete();
            return response()->json((['message' => 'Product deleted', 'success' => 1, 'status' => 200,]));
        }
    }
}
