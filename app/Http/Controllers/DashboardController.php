<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Services\DashboardService;


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




    public function getWeeklyChargeData()
    {
        $cacheData = $this->dashboardService->getWeeklyChargeData(Self::colors, Self::borderColors);
        return response()->json($cacheData);
    }

    public function getPortsPerStation()
    {
        $cacheData = $this->dashboardService->getPortsPerStation();
        return response()->json($cacheData);
    }
    public function userDashboardData()
    {
        $cacheData = $this->dashboardService->userDashboardData();


        return response()->json($cacheData);
    }
    public function getPaymentStatus()
    {
        $cacheData = $this->dashboardService->getPaymentStatusPercentages();


        return response()->json($cacheData);
    }
    public function adminDashboardData()
    {
        $cacheData = $this->dashboardService->adminDashboardData();


        return response()->json($cacheData);
    }
    public function PaidAmountEachMonth()
    {
        $cacheData = $this->dashboardService->PaidAmountEachMonth();


        return response()->json($cacheData);
    }
    public function PaidAmountEachWeek()
    {
        $cacheData = $this->dashboardService->PaidAmountEachWeek();


        return response()->json($cacheData);
    }




    public function getMonthlyChargeData()
    {
        $cacheData = $this->dashboardService->getMonthlyChargeData(Self::colors, Self::borderColors);

        return response()->json($cacheData);
    }
}
