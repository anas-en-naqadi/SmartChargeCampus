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




    public function getStockByCategory()
    {
        $cacheKey = 'stock_by_category';
        $cacheData = getCachedData($cacheKey, function () {
        $stockAndProductCountByCategory = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(DB::raw('categories.category_name as category_name, SUM(products.stock_quantity) as total_stock, COUNT(products.id) as product_count'))
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
                    'label' => 'إجمالي المخزون',
                    'backgroundColor' => $backgroundColor1,
                    'data' => $totalStock,
                ],
                [
                    'label' => 'عدد المنتجات',
                    'backgroundColor' => $backgroundColor2,
                    'data' => $productCount,
                ],

            ],
        ];
    });
    return response()->json($cacheData);
    }

//     public function getMonthlySales()
// {
//     $cacheKey = 'monthly_sales';
//     $cacheData = getCachedData($cacheKey, function () {
//         // Get the first and last day of the current month
//         $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
//         $lastDayOfMonth = Carbon::now()->endOfMonth()->toDateString();

//         // Query to retrieve daily sales data for the current month
//         $dailySales = Sell::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
//             ->selectRaw('DATE(created_at) as date, SUM(total_price) as total_sales')
//             ->groupBy('date')
//             ->orderBy('date')
//             ->get();

//         // Prepare the response data
//         $labels = $dailySales->map(function ($sale) {
//             return Carbon::parse($sale->date)->format('d/M'); // Format 'date' to get day/month
//         })->toArray();
//         $data = $dailySales->pluck('total_sales')->toArray(); // Sales amount for each day

//         return [
//             'labels' => $labels,
//             'data' => $data,
//             'colors' => Self::colors,         // Assuming Self::colors is defined somewhere with your color array
//             'borderColors' => Self::borderColors // Assuming Self::borderColors is defined somewhere with your border color array
//         ];
//     });

//     return response()->json($cacheData);
// }


    public function dashboardData()
    {
        $cacheKey = 'dashboard_data';
        $cacheData = getCachedData($cacheKey, function () {
            // Calculate total revenue for today from all sales
            $todayMoney = Sell::whereDate('created_at', today())
                ->with('products')
                ->get()
                ->sum(function ($sell) {
                    return $sell->products->sum(function ($product) {
                        $profit = $product->selling_price - $product->purchase_price;
                        return $profit * $product->pivot->quantity;
                    });
                });

            // Calculate values for today
            $todaySales = Sell::whereDate('created_at', today())->sum('total_price');
            $todayOwn = Sell::whereDate('created_at', today())->sum('remaining_amount');
            $monthSales = Sell::whereBetween('created_at', [today()->startOfMonth(), today()->endOfMonth()])->sum('total_price');

            // Calculate values for yesterday
            $yesterdayMoney = Sell::whereDate('created_at', today()->subDay())
                ->with('products')
                ->get()
                ->sum(function ($sell) {
                    return $sell->products->sum(function ($product) {
                        $profit = $product->selling_price - $product->purchase_price;
                        return $profit * $product->pivot->quantity;
                    });
                });

            $yesterdaySales = Sell::whereDate('created_at', today()->subDay())->sum('total_price');
            $yesterdayOwn = Sell::whereDate('created_at', today()->subDay())->sum('remaining_amount');
            $previousMonthSales = Sell::whereBetween('created_at', [today()->subMonth()->startOfMonth(), today()->subMonth()->endOfMonth()])->sum('total_price');

            // Calculate values for the current and previous week
            $currentWeekSales = Sell::whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])->sum('total_price');
            $previousWeekSales = Sell::whereBetween('created_at', [today()->subWeek()->startOfWeek(), today()->subWeek()->endOfWeek()])->sum('total_price');

            // Calculate changes compared to yesterday
            $todaySalesChange = $todaySales - $yesterdaySales;
            $moneyChange = $todayMoney - $yesterdayMoney;
            $ownChange = $todayOwn - $yesterdayOwn;

            // Calculate the monthly and weekly changes
            $monthSalesChange = $monthSales - $previousMonthSales;
            $weekSalesChange = $currentWeekSales - $previousWeekSales;

            // Calculate percentage changes
            $salesPercentageChange = $yesterdaySales ? ($todaySalesChange / $yesterdaySales) * 100 : 0; // Avoid division by zero
            $moneyPercentageChange = $yesterdayMoney ? ($moneyChange / $yesterdayMoney) * 100 : 0; // Avoid division by zero
            $ownPercentageChange = $yesterdayOwn ? ($ownChange / $yesterdayOwn) * 100 : 0; // Avoid division by zero
            $monthSalesPercentageChange = $previousMonthSales ? ($monthSalesChange / $previousMonthSales) * 100 : 0; // Avoid division by zero
            $weekSalesPercentageChange = $previousWeekSales ? ($weekSalesChange / $previousWeekSales) * 100 : 0; // Avoid division by zero

            // Return the data with changes
            return [
                'todayMoney' => $todayMoney,
                'todaySales' => $todaySales,
                'todayOwn' => $todayOwn,
                'monthSales' => $monthSales,
                'thisWeekSalesAmount' => $currentWeekSales, // Added weekly sales

                // Adding percentage change values
                'salesPercentageChange' => $salesPercentageChange,
                'moneyPercentageChange' => $moneyPercentageChange,
                'ownPercentageChange' => $ownPercentageChange,
                'monthSalesPercentageChange' => $monthSalesPercentageChange,
                'salesWeekChange' => $weekSalesPercentageChange, // Added weekly sales percentage change
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
                ->selectRaw('DAYNAME(created_at) as day_name, SUM(total_price) as total_sales')
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

            // Arabic translation for days of the week
            $arabicDays = [
                'Monday' => 'الاثنين',
                'Tuesday' => 'الثلاثاء',
                'Wednesday' => 'الأربعاء',
                'Thursday' => 'الخميس',
                'Friday' => 'الجمعة',
                'Saturday' => 'السبت',
                'Sunday' => 'الأحد',
            ];

            // Map the labels to Arabic
            $labels = array_map(function($day) use ($arabicDays) {
                return $arabicDays[$day];
            }, array_keys($salesData));

            $data = array_values($salesData);

            return ['labels' => $labels, 'data' => $data, 'colors' => Self::colors, 'borderColors' => Self::borderColors];
        });

        return response()->json($cacheData);
    }






    public function monthlySalesChart()
    {
        $cacheKey = 'monthly_sales_Chart';
        $cacheData = getCachedData($cacheKey, function () {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Query the database to get monthly sales data for the current year
            $monthlySales = Sell::whereYear('created_at', $currentYear)
                ->selectRaw('MONTHNAME(created_at) as month_name, SUM(total_price) as total_sales')
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

            // Arabic translation for months of the year
            $arabicMonths = [
                'January' => 'يناير',
                'February' => 'فبراير',
                'March' => 'مارس',
                'April' => 'أبريل',
                'May' => 'مايو',
                'June' => 'يونيو',
                'July' => 'يوليو',
                'August' => 'أغسطس',
                'September' => 'سبتمبر',
                'October' => 'أكتوبر',
                'November' => 'نوفمبر',
                'December' => 'ديسمبر',
            ];

            // Map the labels to Arabic
            $labels = array_map(function($month) use ($arabicMonths) {
                return $arabicMonths[$month];
            }, array_keys($salesData));

            $data = array_values($salesData);

            return ['labels' => $labels, 'data' => $data, 'colors' => Self::colors, 'borderColors' => Self::borderColors];
        });

        return response()->json($cacheData);
    }

    public function monthlyRemaining()
    {
        $cacheKey = 'monthly_remaining_amount';
        $cacheData = getCachedData($cacheKey, function () {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Query the database to get monthly sales data for the current year
            $monthlySales = Sell::whereYear('created_at', $currentYear)
                ->selectRaw('MONTHNAME(created_at) as month_name, SUM(remaining_amount) as total_sales')
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

            // Arabic translation for months of the year
            $arabicMonths = [
                'January' => 'يناير',
                'February' => 'فبراير',
                'March' => 'مارس',
                'April' => 'أبريل',
                'May' => 'مايو',
                'June' => 'يونيو',
                'July' => 'يوليو',
                'August' => 'أغسطس',
                'September' => 'سبتمبر',
                'October' => 'أكتوبر',
                'November' => 'نوفمبر',
                'December' => 'ديسمبر',
            ];

            // Map the labels to Arabic
            $labels = array_map(function($month) use ($arabicMonths) {
                return $arabicMonths[$month];
            }, array_keys($salesData));

            $data = array_values($salesData);

            return ['labels' => $labels, 'data' => $data, 'colors' => Self::colors, 'borderColors' => Self::borderColors];
        });

        return response()->json($cacheData);
    }
    public function latestSells(){
        $sells = Sell::latest()->limit(8)->get();
        return response()->json($sells);
}
}

