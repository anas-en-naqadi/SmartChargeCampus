<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SupplierController extends Controller
{
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
        $validatedData = $request->validated();
        $validatedData['user_id'] = getSimpleUser()?->id;

        $client = Client::create($validatedData);
        Redis::del('suppliers');
        saveActivity($client, 'إضافة مورد جديد', 'إضافة');
        return response()->json([
            'message' => 'تم إنشاء المورد بنجاح.',
            'supplier' => $client
        ], 200); // 201 Created
    }


    public function show(int $id): JsonResponse
    {
        $client = $this->findClientById($id);
        Redis::del('suppliers');
        saveActivity($client, 'عرض تفاصيل المورد', 'عرض');
        return response()->json($client, 200); // 200 OK
    }

    public function addNewDebt(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|numeric|exists:clients,id',
            'debt' => 'required|numeric|min:0',
        ], [
            'supplier_id.required' => 'معرف المورد مطلوب.',
            'supplier_id.numeric' => 'يجب أن يكون معرف المورد رقماً.',
            'supplier_id.exists' => 'المورد غير موجود.',

            'debt.required' => 'المبلغ مطلوب.',
            'debt.numeric' => 'يجب أن يكون المبلغ رقماً.',
            'debt.min' => 'يجب أن يكون المبلغ أكبر من أو يساوي صفر.',
        ]);
        $supplier = $this->findClientById($validatedData['supplier_id']);
        $supplier->total_credit += $validatedData['debt'];

        $supplier->save();
        Redis::del('suppliers');
        saveActivity($supplier, 'تم إضافة دين جديد للمورد بنجاح', 'إضافة');
        return response()->json([
            'message' => 'تم إضافة دين جديد للمورد بنجاح'
        ], 200);
    }


    public function update(ClientRequest $request, int $id): JsonResponse
    {
        $client = $this->findClientById($id);

        $client->update($request->validated());
        Redis::del('suppliers');
        saveActivity($client, 'تحديث بيانات المورد', 'تحديث');
        return response()->json([
            'message' => 'تم تحديث بيانات المورد بنجاح.',
            'supplier' => $client
        ], 200);
    }


    public function destroy(int $id): JsonResponse
    {
        $client = $this->findClientById($id);
        $client->delete();
        Redis::del('suppliers');
        saveActivity($client, 'حذف المورد', 'حذف');
        return response()->json([
            'message' => 'تم حذف المورد بنجاح.'
        ], 200);
    }


    private function findClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }
}
