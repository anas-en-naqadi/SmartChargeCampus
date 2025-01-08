<?php

use Illuminate\Database\Eloquent\Model;

use Stevebauman\Purify\Facades\Purify;

use Illuminate\Support\Facades\Redis;

function getSimpleUser()
{
    return auth()->check() ? auth()->user() : null;
}
    function getCachedData($cacheKey, $callback)
    {
        if (Redis::exists($cacheKey)) {
            return json_decode(Redis::get($cacheKey));
        }

        $data = $callback();

        Redis::set($cacheKey, json_encode($data));
        Redis::expire($cacheKey, 60 * 60);
        return $data;
    }
function saveActivity(Model $subject, $description = '', $action = '')
{
    $user = getSimpleUser(); // Assuming this function returns the authenticated user

    $ipAddress = request()->ip(); // Get the IP address of the user

    // Log the activity
    activity()
        ->performedOn($subject)               // The model on which the action is performed
        ->causedBy($user)                     // The user who caused the action
        ->withProperties([                    // Additional properties (like IP address)
            'ip' => $ipAddress,
            'action' => $action,              // Adding the action as a custom property
        ])
        ->log($description)                   // Description of the action
        ->save();                             // Save the activity
}


function clearDashboards(){
    Redis::del('weekly_charge_data');
    Redis::del('monthly_charge_data');
    Redis::del('user_dashboard_data');
    Redis::del('admin_dashboard_data');
    Redis::del('paid_amount_each_week');
    Redis::del('paid_amount_each_month');
    Redis::del('payment_status_percentage');
    Redis::del('ports_per_station');
}



function cleanInputs(array &$array): void
{
    foreach ($array as $field => $value) {
        if (!is_array($value) && !is_uploaded_file($value)) {
            $array[$field] = Purify::clean($value);
        } else if (is_array($value)) {
            foreach ($value as $val) {
                $val = Purify::clean($val);
            }
        }
    }
}
