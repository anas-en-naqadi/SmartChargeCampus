<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function setRead_at(Request $request)
    {
        $date = $request->validate(['now' => 'date']);
        // $user = getSimpleUser();

        $admin = User::where('is_admin', 1)->firstOrFail(); // Fetch the first admin user
        $notifications = $admin->unreadNotifications;
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
        return response()->json(['status' => 'success', 'message' => 'All Notifications Deleted Successfully !!']);
    }

    public function adminNotifications()
    {
        // Assuming there's a 'notifications' relationship on the User model
        // Fetch the first admin user
        $notifications = Auth::user()->unreadNotifications; // Retrieve unread notifications associated with the admin user
        return response()->json($notifications);
    }
}
