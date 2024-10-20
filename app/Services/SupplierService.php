<?php

namespace App\Services;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Redis;

class SupplierService
{

    public function __construct() {}


    public function storeSupplier(ClientRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = getSimpleUser()?->id;
        $validatedData['role'] = 'transporter';
        $client = Client::create($validatedData);
        Redis::del('suppliers');
        saveActivity($client, 'إضافة مورد جديد', 'إضافة');

        return $client;
    }



    public function updateSupplier(ClientRequest $request, Client $supplier)
    {


        $supplier->update($request->validated());
        Redis::del('suppliers');
        saveActivity($supplier, 'تم تحديث بيانات المورد', 'تحديث');
        return $supplier;
    }

    public function destroySupplier(Client $supplier)
    {

        $supplier->delete();
        Redis::del('suppliers');
        saveActivity($supplier, 'حذف المورد', 'حذف');
    }


    public function addNewDebtToSupplier(Request $request)
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
    }

    private function findClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }
}
