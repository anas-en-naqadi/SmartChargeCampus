<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SellController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Models\Sell;

use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // route resources
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('expense', ExpenseController::class);
    Route::resource('/supplier',SupplierController::class);
    Route::resource('/client', ClientController::class);
    Route::resource('/sell',SellController::class);


   // Dashboard Routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/weekly-sales', 'weeklySalesChart');
        Route::get('/monthly-sales', 'monthlySalesChart');
        Route::get('/this-month', 'getMonthlySales');
        Route::get('/month-remaining', 'monthlyRemaining');
        Route::get('/user-registrations', 'monthlyUserRegistrations');
        Route::get('/stock-by-category', 'getStockByCategory');
        Route::get('/latest-sells', 'latestSells');

        Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);
    });

    // User Routes

    Route::controller(UserController::class)->group(function () {
        Route::get('/latest-clients',  'getLastCustomers');
        Route::get('/user',  'getLoggedUser');
        Route::post('/update-pass',  'updatePass');
        Route::post('/changeStatus-user',  'changeUserStatus');
        Route::post('/store-user',  'storeUser');
});

    // Notification Routes

    Route::controller(NotificationController::class)->group(function(){
        Route::get('/notifications', 'adminNotifications');
        Route::get('/delete-notifications', 'deleteAllNotifiable');
        Route::post('/setReadAt', 'setRead_at');
    });

    // Random Routes

    Route::post('/existing-purchase', [PurchaseController::class, 'storeExistingProduct']);
    Route::post('/new-debt', [SupplierController::class, 'addNewDebt']);
    Route::get('/activity', function () {
        $cacheKey = 'activities';

        $cachedData = getCachedData($cacheKey, function () {
            $activities = Activity::causedBy(getSimpleUser())->with('causer')->latest()->get();

            return $activities;
        });

        return response()->json($cachedData);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/filterSellsByDates', [SellController::class, 'filterSellsByDates']);
});







Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/pdf-invoice', [SellController::class, 'downloadInvoiceAsPdf']);

    Route::get("/invoice12", function () {
        $invoice = Sell::with('products','client')->first();
        return view("invoice",['invoice' => $invoice]);
    });





});
