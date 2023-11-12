<?php

namespace App\Traits;

use App\Jobs\SendNotification;

trait NotificationTrait
{
    public function sendNotification($recipient /* user / admin collection */, $message, $module = null)
    {
        SendNotification::dispatch($recipient, $message, $module);
    }
}