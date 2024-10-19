<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Sell;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class DashboardService
{

    public function __construct() {}


    public function stockByCategory()
    {
        $cacheKey = 'stock_by_category';
        $cacheData = getCachedData($cacheKey, function () {
        $stockAndProductCountByCategory = getSimpleUser()->products()->join('categories', 'products.category_id', '=', 'categories.id')
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
    return $cacheData;
    }



    public function dashboardData()
    {

        $cacheKey = 'dashboard_data';
        $cacheData = getCachedData($cacheKey, function () {
            // Calculate total revenue for today from all sales
            $todayMoney = getSimpleUser()->sells()->whereDate('created_at', today())
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
        return $cacheData;
    }

    public function weeklySales($colors,$borderColors)
    {
        $cacheKey = 'weekly_sales';
        $cacheData = getCachedData($cacheKey, function () use($colors,$borderColors) {
            // Get the current week's start and end dates
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            // Query the database to get weekly sales data
            $weeklySales = getSimpleUser()->sells()->whereBetween('created_at', [$startOfWeek, $endOfWeek])
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

            return ['labels' => $labels, 'data' => $data, 'colors' =>$colors, 'borderColors' => $borderColors];
        });
        return $cacheData;
    }

    public function monthlyRemaining($colors,$borderColors){
        $cacheKey = 'monthly_remaining_amount';
        $cacheData = getCachedData($cacheKey, function () use($colors,$borderColors) {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Query the database to get monthly sales data for the current year
            $monthlySales = getSimpleUser()->sells()->whereYear('created_at', $currentYear)
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

            return ['labels' => $labels, 'data' => $data, 'colors' => $colors, 'borderColors' => $borderColors];
        });
        return $cacheData;
    }

    public function monthlySales($colors,$borderColors){
        $cacheKey = 'monthly_sales_Chart';
        $cacheData = getCachedData($cacheKey, function () use($colors,$borderColors) {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Query the database to get monthly sales data for the current year
            $monthlySales = getSimpleUser()->sells()->whereYear('created_at', $currentYear)
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

            return ['labels' => $labels, 'data' => $data, 'colors' => $colors, 'borderColors' => $borderColors];
        });
        return $cacheData;
    }
}
