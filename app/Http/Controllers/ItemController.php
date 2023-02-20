<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Item;
use Auth;

class ItemController extends Controller
{
    public function create(Request $request) {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'serial_number' => 'required|string',
        ]);

        $user = Auth()->user();

        $product = Product::where('id', $request->product_id)->where('user_id', $user->id)->first();
        if ($product) {
            $data['sold'] = false;
            $savedItem = Item::create($data);
            $product->update(array('count' => $product->count + 1));
            return response()->json($savedItem, 200);
        }

        return response()->json([
            'api_status' => '400',
            'message' => 'Invalid request',
        ], 400);
    }

    public function delete(Item $item) {
        $user = Auth()->user();

        $product = Product::where('id', $item->product_id)->where('user_id', $user->id)->first();

        if ($product) {
            if (!$item->sold) {
                $product->update(array('count' => $product->count - 1));
            }
            $item->delete();
            return response()->json('Success', 200);
        }

        return response()->json([
            'api_status' => '400',
            'message' => 'Invalid Request',
        ], 400);
    }

    public function getItems(Product $product) {
        $user = Auth()->user();

        if ($product->user_id == $user->id) {
            $items = Item::where('product_id', $product->id)->get();
            return response()->json($items, 200);
        }
        
        return response()->json([
            'api_status' => '403',
            'message' => 'Unauthorized',
        ], 403);
    }

}
