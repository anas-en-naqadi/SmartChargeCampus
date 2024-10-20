<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Client;
use App\Services\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
   private $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService  = $supplierService;
    }
    public function index()
    {
        $cacheKey = 'suppliers';

        $cachedData = getCachedData($cacheKey, function () {
            $clients = getSimpleUser()->transporters()->latest()->get()->map(function ($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'phone' => $query->phone,
                    'cni' => $query->cni,
                    'purchases' =>PurchaseResource::collection($query->purchases),
                    'purchase_count' => $query->sells()->count(),
                    'total_credit' => $query->total_credit,
                    'created_at' => $query->created_at
                ];
            });


            return $clients;
        });
        return response()->json($cachedData);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = $this->supplierService->storeSupplier($request);
        return response()->json([
            'message' => 'تم إنشاء المورد بنجاح.',
            'supplier' => $client
        ], 200); // 201 Created
    }


    public function show(Client $supplier)
    {
        saveActivity($supplier, 'عرض تفاصيل المورد', 'عرض');
        return response()->json($supplier, 200); // 200 OK
    }

    public function addNewDebt(Request $request)
    {
        $this->supplierService->addNewDebtToSupplier($request);
        return response()->json([
            'message' => 'تم إضافة دين جديد للمورد بنجاح'
        ], 200);
    }


    public function update(ClientRequest $request, Client $supplier): JsonResponse
    {
        $newSupplier = $this->supplierService->updateSupplier($request,$supplier);
        return response(    )->json([
            'message' => 'تم تحديث بيانات المورد بنجاح.',
            'supplier' => $newSupplier
        ], 200);
    }


    public function destroy(Client $supplier): JsonResponse
    {
        $this->supplierService->destroySupplier($supplier);
        return response()->json([
            'message' => 'تم حذف المورد بنجاح.'
        ], 200);
    }



}
