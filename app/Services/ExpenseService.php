<?php

namespace App\Services;

use App\Http\Requests\ExpenseRequest;

use App\Models\Expense;

use Illuminate\Support\Facades\Redis;

class ExpenseService
{

    public function __construct() {}


    public function storeExpense(ExpenseRequest $request)
    {
        $data = $request->validated();
        cleanInputs($data);
        $user = getSimpleUser();
        $data["user_id"] = $user->id;
        $expense = Expense::create($data);
        Redis::del('expenses');
        saveActivity($expense, 'تمت إضافة مصروف جديد', 'إضافة');

        return $expense;
    }





    public function destroySupplier(Expense $expense)
    {

        $expense->delete();
        Redis::del('expenses');
        saveActivity($expense, 'تم حذف المصروف', 'حذف');
    }


}
