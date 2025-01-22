<?php


namespace App\Notifications\Channels;

use OneSignal;
use Log;
class OnesignalChannel
{
    public static function send($appId, $apiKey, $notifiable, $notification)
    {
        $instance = array();
        $instance['title'] = $notification->title;
        $instance['message'] = $notification->message;
        //$instance['url'] = 'titanium://token';
            // $notification->toOnesignal($notifiable);

        if (config('app.env') === 'production' || config('app.env') === 'staging' || config('app.env') === 'local') {
            // $response = OneSignal::sendNotificationToExternalUser(
            //     $instance['message'],
            //     [$notifiable->id],
            //     // $instance['url'] ?? null,
            //     $notification->deeplink ?? null,
            //     $instance['data'] ?? null,
            //     $instance['buttons'] ?? null,
            //     $instance['schedule'] ?? null,
            //     $instance['title'] ?? null,
            // );

            // $response = OneSignal::sendNotificationCustom(
            //     $appId,
            //     $apiKey,
            //     $instance['message'],
            //     [$notifiable->id],
            //     // $instance['url'] ?? null,
            //     $notification->deeplink ?? null,
            //     $instance['data'] ?? null,
            //     $instance['buttons'] ?? null,
            //     $instance['schedule'] ?? null,
            //     $instance['title'] ?? null,
            // );
            $contents = [ 
                "en" => $instance['message'] ?? null, 
             ]; 

            $headings = [
                "en" => $instance['title'] ?? null, // Insert the title here
            ];
            $params = array(
                'app_id' => $appId,
                'headings' => $headings,
                'contents' => $contents,
                'include_external_user_ids' => [$notifiable->id],
                'api_key' => $apiKey,
            );
         
            OneSignal::sendNotificationCustom($params); 
        }
    }

}
