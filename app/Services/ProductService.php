<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class ProductService
{

    public function __construct() {}


    public function storeProduct(object $request)
    {
        $validatedData = $request->validated();
        cleanInputs($validatedData);
        if (empty($validatedData['expiration_date'])) {
            $validatedData['expiration_date'] = null;
        }
        if (isset($validatedData['image'])) {
            $validatedData['image'] = storeImage($validatedData['image']);
        }
        $validatedData['user_id'] = getSimpleUser()?->id;
        $product =   Product::create($validatedData);
        Redis::del('products');
        saveActivity($product, "{$product->name} المنتجتم إنشاء ", "إضافة");

        return ProductResource::make($product);
    }



    public function updateProduct(object $request, Product $product)
    {

        $validatedData['user_id'] = getSimpleUser();
        $validatedData = $request->validated();

        cleanInputs($validatedData);
        if (empty($validatedData['expiration_date'])) {
            $validatedData['expiration_date'] = null; // Explicitly set to null if empty
        }
        if (isset($validatedData['image']) && preg_match('/^data:image\/(\w+);base64,/', $validatedData['image'], $type)) {
            $validatedData['image'] = storeImage($validatedData['image']);

            if ($product->image) {
                File::delete(public_path($product->image));
            }
        }else
            $validatedData['image'] = $product->image;


        // Update the product
        $product->update($validatedData);
        Redis::del('products');
        saveActivity($product, "{$product->name} المنتج تم تحديث بيانات", "تحديث");

        return ProductResource::make($product);
    }

    public function destroyProduct(Product $product)
    {
        if ($product) {

            $product->delete();
            if ($product->image) {
                File::delete(public_path($product->image));
            }
            saveActivity($product, " {$product->name} المنتج تم حذف", "حذف");

            Redis::del('products');
        }
        return true;
    }
}
