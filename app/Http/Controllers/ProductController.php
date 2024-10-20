<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{

    private $productService;

    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    public function index()
    {
        $cacheKey = 'products';
        $cacheData = getCachedData($cacheKey, function () {
            $products=
            getSimpleUser()->products()->latest()->get();

            return ProductResource::collection($products);
        });

        return response()->json($cacheData);
    }



    public function store(ProductRequest $request)
    {
        $product = $this->productService->storeProduct($request);
        return response()->json(['message' => 'تمت إضافة المنتج بنجاح', 'product' => $product]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $newProduct = $this->productService->updateProduct($request,$product);

        return response()->json(['message' => 'تم تحديث المنتج بنجاح', 'product' => ProductResource::make($newProduct)]);
    }

    public function show(Product $product)
    {
        saveActivity($product, 'عرض تفاصيل المنتجات', 'عرض');

            return response()->json(ProductResource::make($product),200);


    }

    public function destroy(Product $product)
    {
        if($this->productService->destroyProduct($product))
        return response()->json(['message' => 'تم حذف المنتج بنجاح']);


    }
}
