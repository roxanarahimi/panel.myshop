<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\BaseProduct;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function categories(Request $request)
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $categories = Category::orderBy('id')->where('visible', 1)->get();
            return response(CategoryResource::collection($categories), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function products(Request $request)
    { //where: categories, stock, off---- sort: new,sale,productsrice
        try {
//            return $request['stock'];
            $products = BaseProduct::orderByDesc('id');

            if ($request['brand_id']) {
                $products = $products->where('brand_id', $request['brand_id']);
            }
            if ($request['category_ids']) {
                $products = $products->whereIn('category_id', array_map('intval', explode(',', $request['category_ids'])));
            }
            if ($request['stock'] == 'true') {
                $products = $products->whereHas('products', function ($query) {
                    $query->where('stock', '>', 0);
                });
            }

            if ($request['term'] && count_chars($request['term'] >= 3)) {
                $products = $products->where('title', 'like', '%' . $request['term'] . '%');
            }
            if ($request['brand'] && count_chars($request['brand'] >= 3)) {
                $products = $products->where('title', 'like', '%' . $request['brand'] . '%');
            }

            if ($request['off'] == 'true') {
                $products = $products->where('off', '>', 0)->orWhereHas('products', function ($query) {
                    $query->where('off', '>', 0);
                });
            }
//
//            if ($request['sort'] == 'sale') {
//                $products->withSum('products', 'sale')
//                    ->orderByDesc('products_sum_sale');
//            }
//            if ($request['sort'] == 'cheap') {
//                $products = $products->orderBy('price');
//            }
//
//            if ($request['sort'] == 'expensive') {
//                $products = $products->orderByDesc('price');
//            }

            $products = $products->paginate(12);
            return response(BaseProductResource::collection($products), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function specialProducts(Request $request)
    {
        try {
            $products = BaseProduct::orderByDesc('id'); //special?
            if ($request['category_id']) {
                $products = $products->where('category_id', $request['category_id']);
            }
            $products = $products->take(4)->get();

            return response(BaseProductResource::collection($products), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function updateCart(Request $request)
    {
        try {
            $cart = Order::where('user_id', $request['user_id'])->where('type', 'cart')->first();
            foreach ($request->orderItems as $item) {
                $orderItem = OrderItem::where('order_id', $cart['id'])->where('product_id', $item['product_id'])->first();
                if ($orderItem) {
                    $orderItem->update(['quantity' => $item['quantity']]);
                } else {
                    OrderItem::create([
                        'order_id' => $cart['id'],
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
                $items = array_column($request['orderItems'], 'product_id');
                $remove = OrderItem::where('order_id', $cart['id'])->whereNotIn('product_id', $items)->get();
                $remove->each->delete();
            }
            return $cart;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $cart = Order::where('user_id', $request['user_id'])->where('type', 'cart')->first();
            $orderItem = OrderItem::where('order_id', $cart['id'])->where('product_id', $request['product_id'])->first();
            if ($orderItem) {
                $orderItem->update(['quantity' => $orderItem['quantity'] + $request['quantity']]);
            } else {
                OrderItem::create([
                    'order_id' => $cart['id'],
                    'product_id' => $request['product_id'],
                    'quantity' => $request['quantity'],
                ]);
            }
            $cart = Order::findOrFail($cart['id']);
            return response($cart, 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
