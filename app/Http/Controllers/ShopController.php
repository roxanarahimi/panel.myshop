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
            $products = BaseProduct::orderByDesc('created_at')->where('visible', 1);

            if ($request['category_ids']) {
                $products = $products->whereIn('category_id', $request['category_ids']);
            }
            if ($request['stock']) {
                $products = $products->whereHas('products', function ($query) use ($request) {
                    $query->where('stock', '>', 0);
                });
            }
            if ($request['off']) {
                $products = $products->whereHas('products', function ($query) use ($request) {
                    $query->where('off', '>', 0);
                });
            }
            if ($request['sale']) {
                $products = $products->orderByDesc('sale');
            }
            if ($request['cheap']) {
                $products = $products->orderBy('price');
            }

            if ($request['expensive']) {
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
            $products = Product::orderByDesc('id'); //special?
            if($request['category_id']) {
                $products = $products->WhereHas('info', function ($query) use ($request) {
                    $query->where('category_id', $request['category_id']);
                });
            }
            $products = $products->take(12)->get();

            return response(ProductResource::collection($products), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

}
