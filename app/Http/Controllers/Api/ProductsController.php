<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use LukeVear\LaravelTransformer\TransformerEngine;

class ProductsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index () : JsonResponse
    {
        $products = Product::all();
        return new JsonResponse(new TransformerEngine($products, new ProductTransformer()));
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show (Product $product) : JsonResponse
    {
        return new JsonResponse(new TransformerEngine($product, new ProductTransformer()));
    }
}
