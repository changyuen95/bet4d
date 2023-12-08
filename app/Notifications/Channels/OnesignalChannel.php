<?php


namespace App\Notifications\Channels;

use OneSignal;

class OnesignalChannel
{
    public static function send($notifiable, $notification)
    {
        $instance = array();
        $instance['title'] = $notification->title;
        $instance['message'] = $notification->message;
        //$instance['url'] = 'titanium://token';
            // $notification->toOnesignal($notifiable);

        if (env('APP_ENV') === 'production' || env('APP_ENV') === 'staging') {
            OneSignal::sendNotificationToExternalUser(
                $instance['message'],
                [$notifiable->id],
                $instance['url'] ?? null,
                $instance['data'] ?? null,
                $instance['buttons'] ?? null,
                $instance['schedule'] ?? null,
                $instance['title'] ?? null,
            );
        }
    }
}