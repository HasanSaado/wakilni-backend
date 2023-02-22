<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Auth;

class ProductController extends Controller
{

    public function index(Request $request) {
        $data = $request->validate([
            'search' => 'required|string',
        ]);

        $user = Auth()->user();

        $products = Product::search($request->search)->where('user_id', $user->id)->get();

        return $products;
    }

    public function create(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $user = Auth()->user();

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $data['user_id'] = $user->id;
        $data['image'] = $imageName;
        $data['count'] = 0;
        $savedProduct = Product::create($data);

        return response()->json(['status' => 'Success'], 200);
    }

    public function delete(Product $product) {
        $user = Auth()->user();

        if ($product->user_id == $user->id) {
            $product->delete();
            return response()->json('Success', 200);
        }
        return response()->json([
            'api_status' => '403',
            'message' => 'Unauthorized',
        ], 403);
    }

    public function update(Request $request) {
        if ($request->has('product_id')) {
            $data = array();
            $user = Auth()->user();
            $product = Product::where('id', $request->product_id)->first();

            if ($product->user_id == $user->id) {
                if ($request->has('image')) {
                    $request->validate([
                        'image' => 'image|mimes:png,jpg,jpeg'
                    ]);
                    $imageName = time().'.'.$request->image->extension();
                    $data['image'] = $imageName;
                    File::delete(public_path('images'), $product->image);
                }
                if ($request->has('name')) {
                    $request->validate([
                        'name' => 'string'
                    ]);
                    $data['name'] = $request->name;
                }
                if ($request->has('description')) {
                    $data['description'] = $request->description;
                }
                $product->update($data);
                return response()->json('Success', 200);
            }

            return response()->json([
                'api_status' => '403',
                'message' => 'Unauthorized',
            ], 403);
        }
        return response()->json([
            'api_status' => '400',
            'message' => 'Invalid request',
        ], 400);
    }

    public function getProduct(Product $product) {
        $user = Auth()->user();

        if ($user->id == $product->id) {
            return response()->json($product, 200);
        }

        return response()->json([
            'api_status' => '403',
            'message' => 'Unauthorized',
        ], 403);
    }

    public function getProducts() {
        $user = Auth()->user();

        $products = Product::where('user_id', $user->id)->get();

        return response()->json($products, 200);
    }
}
