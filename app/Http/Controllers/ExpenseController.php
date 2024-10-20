<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ExpenseController extends Controller
{
    private $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService  = $expenseService;
    }

    public function index(){
        $cacheKey="expenses";
        $cacheData = getCachedData($cacheKey,function(){
            $user = getSimpleUser();
            $expenses = $user->expenses()->latest()->get();
            return $expenses;
        });

        return response()->json($cacheData);
    }

    public function store(ExpenseRequest $request){
        $expense = $this->expenseService->storeExpense($request);

        return response()->json(["expense"=>$expense,"message"=> "تمت إضافة مصروف جديد بنجاح"]);
    }


    public function destroy(Request $request,Expense $expense){
        $this->expenseService->destroySupplier($expense);
        return response()->json(['message' => 'تم حذف المصروف بنجاح']);
    }
}
