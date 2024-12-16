<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $cacheKey = 'students';
        $cacheData = getCachedData($cacheKey, function () {
            return Student::with('user')->get();
        });

        saveActivity(new Student(),'L\'administrateur ' .getSimpleUser()->name . ' a Consultée les Etudiants', 'consulte-etudiants');

        return response()->json(StudentResource::collection($cacheData));
    }
}
