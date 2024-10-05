<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sell;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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



    public function monthlyUserRegistrations()
    {
        $cacheKey = 'monthly_user_registration';
        $cacheData = getCachedData($cacheKey, function () {
        $currentYear = Carbon::now()->year;

        $userRegistrations = User::whereYear('created_at', $currentYear)
            ->selectRaw('MONTHNAME(created_at) as month_name, COUNT(*) as user_count')
            ->groupByRaw('MONTHNAME(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        $labels = $userRegistrations->pluck('month_name')->toArray();
        $data = $userRegistrations->pluck('user_count')->toArray();

       return[
            'labels' => $labels,
            'data' => $data,
        ];
    });
    return response()->json($cacheData);
    }
    public function getStockByCategory()
    {
        $cacheKey = 'stock_by_category';
        $cacheData = getCachedData($cacheKey, function () {
        $stockAndProductCountByCategory = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(DB::raw('categories.category_name as category_name, SUM(products.quantity) as total_stock, COUNT(products.id) as product_count'))
            ->groupBy('categories.category_name')
            ->get();

        $labels = $stockAndProductCountByCategory->pluck('category_name')->toArray();
        $totalStock = $stockAndProductCountByCategory->pluck('total_stock')->toArray();
        $productCount = $stockAndProductCountByCategory->pluck('product_count')->toArray();

        // Example colors, adjust as needed
        $backgroundColor1 = '#36a2eb'; // Total Stock
        $backgroundColor2 = '#ffcd56'; // Product Count

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Stock',
                    'backgroundColor' => $backgroundColor1,
                    'data' => $totalStock,
                ],
                [
                    'label' => 'Product Count',
                    'backgroundColor' => $backgroundColor2,
                    'data' => $productCount,
                ],
            ],
        ];
    });
    return response()->json($cacheData);
    }

    public function getMonthlySales()
{
    $cacheKey = 'monthly_sales';
    $cacheData = getCachedData($cacheKey, function () {
        // Get the first and last day of the current month
        $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $lastDayOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Query to retrieve daily sales data for the current month
        $dailySales = Sell::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare the response data
        $labels = $dailySales->map(function ($sale) {
            return Carbon::parse($sale->date)->format('d/M'); // Format 'date' to get day/month
        })->toArray();
        $data = $dailySales->pluck('total_sales')->toArray(); // Sales amount for each day

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => Self::colors,         // Assuming Self::colors is defined somewhere with your color array
            'borderColors' => Self::borderColors // Assuming Self::borderColors is defined somewhere with your border color array
        ];
    });

    return response()->json($cacheData);
}

    public function orderStatusPieChart()
    {
        $cacheKey = 'order_status';
        $cacheData = getCachedData($cacheKey, function () {
        // Retrieve counts of orders with different statuses
        $returnedCount = Order::where('status', 'returned')->count();
        $confirmedCount = Order::where('status', 'confirmed')->count();
        $canceledCount = Order::where('status', 'canceled')->count();
        $processedCount = Order::where('status', 'pending')->count();


        // Calculate total number of orders
        $totalCount = $returnedCount + $confirmedCount + $canceledCount + $processedCount;

        if ($totalCount > 0) {
            // Calculate percentage for each status
            $returnedPercentage = $returnedCount / $totalCount * 100;
            $confirmedPercentage = $confirmedCount / $totalCount * 100;
            $canceledPercentage = $canceledCount / $totalCount * 100;
            $processedPercentage = $processedCount / $totalCount * 100;

        } else {
            // Set percentages to 0 if there are no orders
            $returnedPercentage = 0.1;
            $confirmedPercentage = 0.1;
            $canceledPercentage = 0.1;
            $processedPercentage = 0.1;
        }

        // Prepare data for the pie chart
        $labels = ['Returned', 'Confirmed', 'Canceled','Pending'];
        $data = [$returnedPercentage, $confirmedPercentage, $canceledPercentage,$processedPercentage];

        $colorsStatus = [
            'rgba(255, 99, 132, 0.4)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(54, 162, 235, 0.8)',
                     'rgba(50, 205, 50, 0.2)'
        ]; // Red, Green, Blue

        // Return the data
        return ['labels' => $labels, 'data' => $data, 'backgroundColor' => $colorsStatus];
    });
    return response()->json($cacheData);
    }
    public function dashboardData()
    {
        $cacheKey = 'dashboard_data';
        $cacheData = getCachedData($cacheKey, function () {
        $todayMoney = Order::whereDate('created_at', today())->sum('amount');
        $todaysUsers = User::whereDate('created_at', today())->count();
        $newClients = Order::whereDate('created_at', today())->distinct()->count('user_id');
        $todaysSalesAmount =  Sell::whereBetween('created_at', [today()->startOfMonth(), today()->endOfMonth()])->sum('total_amount');
        $thisWeekSalesAmount = Sell::whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])->sum('total_amount');
        // Retrieve data from the previous period (e.g., yesterday)
        $yesterdayMoney = Order::whereDate('created_at', today()->subDay())->sum('amount');
        // Retrieve the number of users created on the same day of the previous month
        $previousMonthUsers = User::whereDate('created_at', today()->subMonth()->startOfMonth())->count();
        $previousWeekSalesAmount = Sell::whereBetween('created_at', [today()->startOfWeek()->subWeek(), today()->endOfWeek()->subWeek()])->sum('total_amount');

        $yesterdayNewClients = Order::whereDate('created_at', today()->subDay())->distinct()->count('user_id');
        $yesterdaySalesAmount = Sell::whereDate('created_at', today()->subDay())->sum('total_amount');

        // Calculate the changes
        $salesWeekChange = $thisWeekSalesAmount != 0 ? (($thisWeekSalesAmount - $previousWeekSalesAmount) / $thisWeekSalesAmount) * 100 : 0;


        $moneyChange = $todayMoney != 0 ? (($todayMoney - $yesterdayMoney) / $todayMoney) * 100 : 0;
        $usersChange = $todaysUsers != 0 ? (($todaysUsers - $previousMonthUsers) / $todaysUsers) * 100 : 0;
        $newClientsChange = $newClients != 0 ? (($newClients - $yesterdayNewClients) / $newClients) * 100 : 0;
        $salesAmountChange = $todaysSalesAmount != 0 ? (($todaysSalesAmount - $yesterdaySalesAmount) / $todaysSalesAmount) * 100 : 0;

        // Ensure the changes are within the range of -100 to 100
        $moneyChange = number_format(min(100, max(-100, $moneyChange)), 1);
        $usersChange = number_format(min(100, max(-100, $usersChange)), 1);
        $newClientsChange = number_format(min(100, max(-100, $newClientsChange)), 1);
        $salesAmountChange = number_format(min(100, max(-100, $salesAmountChange)), 1);
        $salesWeekChange = number_format(min(100, max(-100, $salesWeekChange)), 1);
        // Return the data with changes
        return [
            'todayMoney' => $todayMoney,
            'moneyChange' => $moneyChange,
            'todaysUsers' => $todaysUsers,
            'usersChange' => $usersChange,
            'newClients' => $newClients,
            'newClientsChange' => $newClientsChange,
            'todaysSalesAmount' => $todaysSalesAmount,
            'salesAmountChange' => $salesAmountChange,
            'thisWeekSalesAmount' => $thisWeekSalesAmount,
            'salesWeekChange' => $salesWeekChange,
        ];
    });
    return response()->json($cacheData);
    }
    public function weeklySalesChart()
    {
        $cacheKey = 'weekly_sales';
        $cacheData = getCachedData($cacheKey, function () {
        // Get the current week's start and end dates
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Query the database to get weekly sales data
        $weeklySales = Sell::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DAYNAME(created_at) as day_name, SUM(total_amount) as total_sales')
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();

        // Initialize sales data for all seven days to 0
        $salesData = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];



        // Fill in the actual sales data for days where sales exist
        foreach ($weeklySales as $sale) {
            $salesData[$sale->day_name] = $sale->total_sales;
        }

        // Format the data for chart.js
        $labels = array_keys($salesData);
        $data = array_values($salesData);

        return ['labels' => $labels, 'data' => $data, 'colors' => Self::colors, 'borderColors' => Self::borderColors];
    });
    return response()->json($cacheData);
    }





    function monthlySalesChart()
    {
        $cacheKey = 'monthly_sales_Chart';
        $cacheData = getCachedData($cacheKey, function () {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Query the database to get monthly sales data for the current year
        $monthlySales = Sell::whereYear('created_at', $currentYear)
            ->selectRaw('MONTHNAME(created_at) as month_name, SUM(total_amount) as total_sales')
            ->groupByRaw('MONTHNAME(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        // Initialize sales data for all twelve months to 0
        $salesData = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];


        // Fill in the actual sales data for months where sales exist
        foreach ($monthlySales as $sale) {
            $salesData[$sale->month_name] = $sale->total_sales;
        }

        // Format the data for chart.js
        $labels = array_keys($salesData);
        $data = array_values($salesData);


      return ['labels' => $labels, 'data' => $data, 'colors' => Self::colors, 'borderColors' => Self::borderColors];
    });
    return response()->json($cacheData);
    }
}
