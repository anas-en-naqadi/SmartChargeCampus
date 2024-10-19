<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Redis;

class PurchaseController extends Controller
{
    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    
    public function index()
    {
        $cacheKey = 'purchases';
        $cachedData = getCachedData($cacheKey, function () {
            $purchases = getSimpleUser()->purchases()
                ->latest()
                ->get();
            return PurchaseResource::collection($purchases);
        });

        saveActivity(new Purchase(), "تم عرض قائمة المشتريات", "تحديد");

        return response()->json($cachedData);
    }
    public function store(PurchaseRequest $request)
    {


        $purchase = $this->purchaseService->storePurchase($request);

        return response()->json([
            'message' => 'تم إنشاء عملية الشراء بنجاح.',
            'purchase' => $purchase,
        ], 200);
    }

    public function storeExistingProduct(PurchaseRequest $request)
    {
        $purchase = $this->purchaseService->storeExistingPurchase($request);

        return response()->json([
            'message' => 'تم تحديث المنتج وإنشاء عملية الشراء بنجاح.',
            'purchase' => $purchase
        ], 200);
    }
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $newPurchase = $this->purchaseService->updatePurchase($request, $purchase);


        return response()->json([
            'message' => 'تم تحديث عملية الشراء بنجاح.',
            'purchase' => $newPurchase
        ], 200);
    }

    public function show(Purchase $purchase)
    {
        return response()->json($purchase, 200); // 200 OK

    }




    public function destroy(Purchase $purchase)
    {
        if ($this->purchaseService->destroyPurchase($purchase))
            return response()->json([
                'message' => 'تم حذف عملية الشراء بنجاح.'
            ], 200); // 200 OK

    }
}
