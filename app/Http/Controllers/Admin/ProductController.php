<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;

use App\Notifications\StockAlertNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    public function index()
    {
        $cacheKey = 'products';
        $cacheData = getCachedData($cacheKey, function () {
            $products = [];

            // Use chunk to process large datasets
            Product::latest()->chunk(100, function ($chunk) use (&$products) {
                foreach ($chunk as $product) {
                    $product->image = !empty($product->image) ? URL::to($product->image) : null;
                    $product->category; // This assumes you have a relationship named `category` defined in the Product model
                    $products[] = $product;
                }
            });

            return $products;
        });

        return response()->json($cacheData);
    }



    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        if (isset($validatedData['image'])) {
            $validatedData['image'] = storeImage($validatedData['image']);
        }
        $validatedData['user_id'] = getSimpleUser()?->id;
        Product::create($validatedData);
        Redis::del('products');
        return response()->json(['message' => 'تمت إضافة المنتج بنجاح']);    }

    public function update(ProductRequest $request, Product $product)
    {
        $validatedData['user_id'] = 81;
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        if (isset($validatedData['image']) && preg_match('/^data:image\/(\w+);base64,/', $validatedData['image'], $type)) {

            $validatedData['image'] = storeImage($validatedData['image']);

            if ($product->image) {
                File::delete(public_path($product->image));
            }
        }

        // Update the product
        $product->update($validatedData);
        Redis::del('products');
        return response()->json(['message' => 'تم تحديث المنتج بنجاح']);    }

    public function show($id)
    {
        try {

            $product = Product::with('category')->findOrFail($id);
         if(!empty($product->image))   $product->image = URL::to($product->image);
            return response()->json($product);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            if ($product->image) {
                File::delete(public_path($product->image));
            }
            Redis::del('products');
            return response()->json(['message' => 'تم حذف المنتج بنجاح']);
                } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'product not found'], 404);
        }
    }

}
