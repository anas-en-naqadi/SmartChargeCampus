<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ExpenseController extends Controller
{
    public function index(){
        $cacheKey="expenses";
        $cacheData = getCachedData($cacheKey,function(){
            $user = getSimpleUser();
            $expenses = $user->expenses;
            return $expenses;
        });

        return response()->json($cacheData);
    }

    public function store(ExpenseRequest $request){
        $data = $request->validated();
        cleanInputs($data);
        $user = getSimpleUser();
        $data["user_id"] = $user->id;
        $expense = Expense::create($data);
        Redis::del('expenses');

        return response()->json(["expense"=>$expense,"message"=> "تمت إضافة مصروف جديد بنجاح"]);
    }


    public function destroy(Request $request,Expense $expense){
        $expense->delete();
        Redis::del('expenses');
        return response()->json(['message' => 'تم حذف المصروف بنجاح']);
    }
}
