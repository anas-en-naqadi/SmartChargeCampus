<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{

    public function index()
    {
        $cacheKey = 'categories';
        $cacheData = getCachedData($cacheKey, function () {
        $categories =getSimpleUser()->categories;
        return $categories;
        });
        saveActivity(new Category(), "تم عرض قائمة الفئات", "تحديد");

        return response()->json($cacheData);

    }

    public function store(CategoryRequest $request)
    {

        $validatedData = $request->validated();
        CleanInputs($validatedData);
        $validatedData['user_id'] = getSimpleUser()->id;
        $category =  Category::create($validatedData);
        Redis::del('categories');
        saveActivity($category, "$category->category_name : تمت إضافة فئة جديدة ", "إضافة");

        return $category ? response()->json(['message'=>'تمت إضافة فئة جديدة بنجاح','category'=>$category],200) : response()->json('oops, something went wrong!!',500);
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            saveActivity(new Category(), "$category->name : تم حذف الفئة ", "حذف");
            $category->delete();
            Redis::del('categories');


            return response()->json(['message' => ' تم حذف الفئة بنجاح' ]);
        } catch (ModelNotFoundException $message) {
            return response()->json(['message' => 'عفواً، حدث خطأ ما']);
        }
    }
}
