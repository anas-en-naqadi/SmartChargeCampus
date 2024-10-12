<?php

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Redis;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

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
    Redis::del("activities");

    $user = getSimpleUser();

    $ipAddress = request()->ip();

    // Log the activity
    activity()
        ->log($description)                   // Description of the action
        ->performedOn($subject)               // The model on which the action is performed
        ->causedBy($user)                     // The user who caused the action
        ->logName($action)                    // The type of action (log name)
        ->withProperties([                    // Additional properties (like IP address)
            'ip' => $ipAddress,
        ])
        ->save();                             // Save the activity

}


function storeImage($image)
{
    if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
        $image = substr($image, strpos($image, ','));
        $type = strtolower($type[1]);
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception('invalid image type');
        }
        $image = str_replace('', '+', $image);
        $image = base64_decode($image);
    } else {
        throw new Exception('did not match data urL with image');
    }
    $dir = 'storage/images/products/';
    $file = Str::random() . '.' . $type;
    $absolutePath = public_path($dir);
    $relativePath = $dir . $file;
    if (!File::exists($absolutePath)) {
        File::makeDirectory($absolutePath, 0755, true);
    }

    file_put_contents($relativePath, $image);
    ImageOptimizer::optimize($absolutePath);
    return $relativePath;
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
