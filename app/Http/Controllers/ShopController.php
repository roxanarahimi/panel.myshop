<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OrderResource;
use App\Models\BaseProduct;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Province;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function categories()
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $categories = Category::orderBy('id')->where('visible', 1)->get();
            return response(CategoryResource::collection($categories), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function brands()
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $brands = Brand::orderBy('id')->get();
            return response($brands, 200);
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

    public function showOrder($code)
    {
        try {
            $order = Order::where('code',$code)->where('type','order')->where('payed_at','!=',null)->first();
            return response(new OrderResource($order), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function provinces()
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $provinces = Province::orderBy('name')->get();
            return response($provinces, 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function cities($id)
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $cities = City::where('province_id', $id)->orderBy('name')->get();
            return response($cities, 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function updateCart($request)
    {
        try {
            $cart = Order::findOrFail($request['id']);
            $total_amount = 0;
            $total_off = 0;
            foreach ($cart->items as $item) {
                //update item
                $product = Product::findOrFail($item['product_id']);
                $price = $product['price'] ? $product['price'] : $product->info->price;
                $off = $product['off'] ? $product['off'] : $product->info->off;
                $item->update([
                    'price' => $price,
                    'off' => $off,
                ]);
                //calculate cart
                $total_amount += $price * $item['quantity'];
                $total_off += $price * $off / 100 * $item['quantity'];
            }
            //update cart
            $cart->update([
                "total_amount" => $total_amount,
                "total_off" => $total_off,
                "amount" => $total_amount - $total_off + $cart['delivery_amount'],
            ]);
            return response(new OrderResource($cart), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $cart = Order::where('user_id', $request['user_id'])->where('type', 'cart')->first();
            if (!$cart) {
                $cart = Order::create([
                    'user_id' => $request['user_id'],
                    'type' => 'cart',
                ]);
            }
            $orderItem = OrderItem::where('order_id', $cart['id'])->where('product_id', $request['product_id'])->first();
            if ($orderItem) {
                $orderItem->update(['quantity' => $orderItem['quantity'] + 1]);
            } else {
                $product = Product::find($request['product_id']);
                OrderItem::create([
                    'order_id' => $cart['id'],
                    'product_id' => $request['product_id'],
                    'price' => $product['price'] ? $product['price'] : $product->info->price,
                    'off' => $product['off'] ? $product['off'] : $product->info->off,
                    'quantity' => 1,
                ]);
            }
            $this->updateCart(['id' => $cart['id']]);
            $c = Order::find($cart['id']);
            return response(new OrderResource($c), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function removeFromCart(Request $request)
    {
        try {
            $item = OrderItem::findOrFail($request['id']);
            if ($item['quantity']==1){
                $item->delete();
            }else{
                $item->update(['quantity'=>$item->quantity-1]);
            }
            $this->updateCart(['id' => $item['order_id']]);
            $cart = Order::find($item['order_id']);
            return response(new OrderResource($cart), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function emptyCart(Request $request)
    {
        $cart = Order::findOrFail($request['id']);
        $cart->items->each->delete();
        $cart->update([
            "total_amount"=> 0,
            "total_off"=> 0,
            "amount"=> 0,
        ]);
        $cart = Order::findOrFail($request['id']);
        return response(new OrderResource($cart), 200);
    }
}
