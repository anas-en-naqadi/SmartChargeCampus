<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Sell;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    private const colors = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',    // February
        'rgba(255, 205, 86, 0.2)',    // March
        'rgba(75, 192, 192, 0.2)',    // April
        'rgba(54, 162, 235, 0.2)',    // May
        'rgba(153, 102, 255, 0.2)',   // June
        'rgba(201, 203, 207, 0.2)',   // July
        'rgba(153, 102, 255, 0.2)',   // June
        'rgba(201, 203, 207, 0.2)',   // July
        'rgba(106, 90, 205, 0.2)',    // August (New color)
        'rgba(255, 165, 0, 0.2)',     // September (New color)
        'rgba(30, 144, 255, 0.2)',
    ];

    private const borderColors = [
        'rgb(255, 99, 132)',    // January
        'rgb(255, 159, 64)',    // February
        'rgb(255, 205, 86)',    // March
        'rgb(75, 192, 192)',    // April
        'rgb(54, 162, 235)',    // May
        'rgb(153, 102, 255)',   // June
        'rgb(201, 203, 207)',   // July
        'rgba(153, 102, 255, 0.2)',   // June
        'rgba(201, 203, 207, 0.2)',   // July
        'rgba(106, 90, 205, 0.2)',    // August (New color)
        'rgba(255, 165, 0, 0.2)',     // September (New color)
        'rgba(30, 144, 255, 0.2)',
    ];




    public function getStockByCategory()
    {
        $cacheData = $this->dashboardService->stockByCategory();
        return response()->json($cacheData);
    }


    public function dashboardData()
    {
        $cacheData = $this->dashboardService->dashboardData();


        return response()->json($cacheData);
    }





    public function weeklySalesChart()
    {
        $cacheData = $this->dashboardService->weeklySales(Self::colors, Self::borderColors);

        return response()->json($cacheData);
    }






    public function monthlySalesChart()
    {
        $cacheData = $this->dashboardService->monthlySales(Self::colors, Self::borderColors);


        return response()->json($cacheData);
    }

    public function monthlyRemaining()
    {

        $cacheData = $this->dashboardService->monthlyRemaining(Self::colors, Self::borderColors);
        return response()->json($cacheData);
    }
    public function latestSells()
    {
        $sells = Sell::latest()->limit(8)->get();
        return response()->json($sells);
    }
}
