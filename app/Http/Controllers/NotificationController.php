<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class NotificationController extends Controller
{

    public function setRead_at(Request $request)
    {
        $date = $request->validate(['now' => 'date']);


        $user = getSimpleUser(); // Fetch the first admin user
        $notifications = $user->unreadNotifications;
        Redis::del('notifications');
        foreach ($notifications as $notif) {
            $notif->read_at = Carbon::parse($date);
            $notif->save(); // Don't forget to save the changes to the database
        }
    }
    public function deleteAllNotifiable()
    {
        $user = getSimpleUser();
        foreach ($user->notifications as $notif) {
            $notif->delete();
        }
        Redis::del('notifications');
        return response()->json([ 'message' => 'تم حذف جميع الإشعارات بنجاح !!']);
        }

    public function adminNotifications()
    {
        $cacheKey = "notifications";
        $cachedData=getCachedData($cacheKey,function(){
            $notifications =getSimpleUser()->notifications; // Retrieve unread notifications associated with the admin user
        return $notifications;
        });
        return response()->json($cachedData);
    }
}
