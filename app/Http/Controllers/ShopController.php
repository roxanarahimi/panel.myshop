<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\BaseProduct;
use App\Models\Category;
use App\Models\Product;
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
    { //where: categories, stock, off---- sort: new,sale,price
        try {
            $products = BaseProduct::orderByDesc('id');

//            if ($request['category_ids']) {
//                $products = $products->whereIn('category_id', $request['category_ids']);
//            }
            if ($request['stock']=== true) {
                $products = $products->whereHas('products', function ($query) use ($request) {
                    $query->where('stock', '>', 0);
                });
            }
            if ($request['off'] === true) {
                $products = $products->whereHas('products', function ($query) use ($request) {
                    $query->where('off', '>', 0);
                });
            }
            if ($request['sale'] === true) {
                $products = $products->orderByDesc('sale');
            }
            if ($request['cheap'] === true) {
                $products = $products->orderBy('price');
            }

            if ($request['expensive'] === true) {
                $products = $products->orderByDesc('price');
            }

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
            if($request['category_id']) {
                $products = $products->where('category_id', $request['category_id']);
            }
            $products = $products->take(4)->get();

            return response(BaseProductResource::collection($products), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

}
