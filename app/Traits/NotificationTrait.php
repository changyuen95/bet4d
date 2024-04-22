<?php

namespace App\Traits;

use App\Jobs\SendNotification;

trait NotificationTrait
{
    public function sendNotification($appId, $apiKey, $recipient /* user / admin collection */, $message, $module = null)
    {
        SendNotification::dispatch($appId, $apiKey, $recipient, $message, $module);
    }
}