<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class ProductsController
 *
 * @package App\Http\Controllers\Api
 */
class ProductsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index () : JsonResponse
    {
        $products = Product::all();
        return new JsonResponse(new TransformerEngine($products, new ProductTransformer()));
    }

    /**
     * @param \App\Models\Product $product
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show (Product $product) : JsonResponse
    {
        return new JsonResponse(new TransformerEngine($product, new ProductTransformer()));
    }
}
