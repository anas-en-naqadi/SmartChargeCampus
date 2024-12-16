<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChargingStationController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\StudentController;


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

    // Port Routes
    Route::resource('port', PortController::class)->middleware('admin');
    Route::post('/mark-as-not-reserved', [PortController::class, 'markPortAsNotReserved'])->middleware('admin');

    // Charging Station Routes
    Route::resource('charge-station', ChargingStationController::class)
        ->except(['store', 'update', 'destroy']);
    Route::post('/charge-station', [ChargingStationController::class, 'store'])->middleware('admin');
    Route::put('/charge-station/{id}', [ChargingStationController::class, 'update'])->middleware('admin');
    Route::delete('/charge-station/{ChargingStation}', [ChargingStationController::class, 'destroy'])->middleware('admin');

    // Reservation Routes
    Route::resource('reserve', ReservationController::class)
        ->except(['destroy']);
    Route::post('/filterReservationsByDates', [ReservationController::class, 'filterReservationsByDates'])
    ->middleware('admin');
    Route::post('/cancel-reservation', [ReservationController::class, 'cancelReservation']);
    Route::post('/mark-reservation-as-paid', [ReservationController::class, 'markReservationAsPayed'])->middleware('admin');

    // Student Routes
    Route::get('/student', [StudentController::class, 'index'])->middleware('admin');

    // Dashboard Routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/charge-per-week', 'getWeeklyChargeData');
        Route::get('/charge-per-month', 'getMonthlyChargeData');
        Route::get('/user-dashboard-data', 'userDashboardData');
        // Admin Dashboard
        Route::middleware('admin')->group(function () {
            Route::get('/admin-dashboard-data', 'adminDashboardData');
            Route::get('/gain-amount-per-week', 'PaidAmountEachWeek');
            Route::get('/gain-amount-per-month', 'PaidAmountEachMonth');
            Route::get('/payment-status', 'getPaymentStatus');
            Route::get('/charge-station-port-count', 'getPortsPerStation');
        });
    });

    // User Routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'getLoggedUser');
        Route::post('/update-pass', 'updatePass');
        Route::post('/update-student-profile', 'updateUserProfile');
        Route::post('/update-user', 'updateUserProfile');
    });

    // Notification Routes
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'adminNotifications');
        Route::get('/delete-notifications', 'deleteAllNotifiable');
        Route::post('/setReadAt', 'setRead_at');
    });

    // Random Routes
    Route::get('/activity', function () {
        $cacheKey = 'activities';

        $cachedData = getCachedData($cacheKey, function () {
            $activities = Activity::causedBy(getSimpleUser())->with('causer')->latest()->get();

            return $activities;
        });

        return response()->json($cachedData);
    })->middleware('admin');

    Route::post('/logout', [AuthController::class, 'logout']);
});







Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});
