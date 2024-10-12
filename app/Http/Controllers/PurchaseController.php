<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PurchaseController extends Controller
{
    public function index(){
        $cacheKey = 'purchases';
        $cachedData = getCachedData($cacheKey,function(){
            $purchases = getSimpleUser()->purchases()
            ->with([
                'transporter:id,name', // Fetch only 'id' and 'name' from transporter
                'category:id,category_name' // Fetch only 'id' and 'category_name' from category
            ])
            ->latest()
            ->select('id', 'name', 'transporter_id', 'category_id', 'created_at', 'stock_quantity', 'purchase_price', 'expiration_date')
            ->get();            return $purchases->map(function($purchase) {
                return $this->structuredPurchase($purchase);
            });
        });

        saveActivity(new Purchase(), "تم عرض قائمة المشتريات", "تحديد");

        return response()->json($cachedData);
    }
    public function store(PurchaseRequest $request){

        $validatedData = $request->validated();
        $validatedData['user_id'] = getSimpleUser()?->id;
        $purchase = Purchase::create($validatedData);
        saveActivity($purchase, "تم إنشاء عملية شراء جديدة بنجاح", "إضافة");
        Redis::del('purchases');
       

        return response()->json([
            'message' => 'تم إنشاء عملية الشراء بنجاح.',
            'purchase' => $this->structuredPurchase($purchase->load('category','transporter')),
        ], 200);
        }

    public function storeExistingProduct(PurchaseRequest $request){
        $validatedData = $request->validated();
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

        return response()->json([
            'message' => 'تم تحديث المنتج وإنشاء عملية الشراء بنجاح.',
            'purchase' =>$this->structuredPurchase($purchase->load('category','transporter')),
        ], 200);
    }
    public function update(PurchaseRequest $request, Purchase $purchase)
{
    $validatedData = $request->validated();

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

    return response()->json([
        'message' => 'تم تحديث عملية الشراء بنجاح.',
        'purchase' => $this->structuredPurchase($purchase->load('transporter','category'))
    ], 200);
}

    public function show(Purchase $purchase)
    {
            return response()->json($purchase, 200); // 200 OK

    }




    public function destroy(Purchase $purchase)
    {
            if($purchase->updated_product){
                $purchase->product->stock_quantity -= $purchase->stock_quantity;
            $purchase->product->save();
            }
            $purchase->delete();
            saveActivity($purchase, "تم حذف عملية الشراء بنجاح", "حذف");

            Redis::del('purchases');

            return response()->json([
                'message' => 'تم حذف عملية الشراء بنجاح.'
            ], 200); // 200 OK

    }


    private function structuredPurchase($purchase):Array{
        return [
            'id'=>$purchase->id,
            'name'=>$purchase->name,
            'purchase_id' => $purchase->id,
            'transporter_name' => $purchase->transporter_name,
            'category_name' => $purchase->category?->category_name, // Get product category name
            'created_at' => $purchase->created_at,
            'stock_quantity' =>$purchase->stock_quantity,
            'purchase_price' => $purchase->purchase_price,
            'expiration_date' => $purchase->expiration_date,
            'updated_product' => $purchase->updated_product,
        ];
    }

}
