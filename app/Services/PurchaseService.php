<?php
namespace App\Services;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Redis;

class PurchaseService {

    public function __construct() {}


    public function storePurchase(PurchaseRequest $request){
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        $validatedData['user_id'] = getSimpleUser()?->id;
        $purchase = Purchase::create($validatedData);
        saveActivity($purchase, "تم إنشاء عملية شراء جديدة بنجاح", "إضافة");
        Redis::del('purchases');
        return PurchaseResource::make($purchase);
    }

    public function storeExistingPurchase(PurchaseRequest $request){

        $validatedData = $request->validated();
        cleanInputs($validatedData);

        $product_id = $validatedData['updated_product'];
        $product = Product::findOrFail($product_id);
        $validatedData['name'] = $product->name;
        $product->stock_quantity += $validatedData['stock_quantity'];
        $product->purchase_price = $validatedData['purchase_price'];
        $product->save();
        unset($validatedData['product_id']);
        Redis::del('purchases');
        Redis::del('products');
        $validatedData['user_id'] = getSimpleUser()?->id;
        $purchase = Purchase::create($validatedData);
        saveActivity($purchase, "تم إنشاء عملية الشراء بنجاح", "إضافة");
        return PurchaseResource::make($purchase);
    }

    public function updatePurchase (PurchaseRequest $request,Purchase $purchase){
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        if ($purchase->updated_product) {
            $purchase->product->stock_quantity -= $purchase->stock_quantity;
            $purchase->product->save();
        }

        $purchase->update($validatedData);

        if ($purchase->updated_product) {
            $purchase->product->stock_quantity += $purchase->stock_quantity;
            $purchase->product->purchase_price = $purchase->purchase_price;
            $purchase->product->save();
        }

        saveActivity($purchase, "تم تحديث عملية الشراء بنجاح", "تحديث");

        Redis::del('purchases');
        Redis::del('products');
        return PurchaseResource::make($purchase);

    }

    public function destroyPurchase(Purchase $purchase){
        if ($purchase->updated_product) {
            $purchase->product->stock_quantity -= $purchase->stock_quantity;
            $purchase->product->save();
        }
        $purchase->delete();
        saveActivity($purchase, "تم حذف عملية الشراء بنجاح", "حذف");

        Redis::del('purchases');
        return true;
    }

}
