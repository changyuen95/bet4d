<?php


namespace App\Notifications\Channels;

use OneSignal;
use Log;
class OnesignalChannel
{
    public static function send($notifiable, $notification, $appId, $apiKey)
    {
        $instance = array();
        $instance['title'] = $notification->title;
        $instance['message'] = $notification->message;
        //$instance['url'] = 'titanium://token';
            // $notification->toOnesignal($notifiable);

        if (env('APP_ENV') === 'production' || env('APP_ENV') === 'staging' || env('APP_ENV') === 'local') {
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
            
            $response = OneSignal::sendNotificationCustom(
                $appId,
                $apiKey,
                $instance['message'],
                [$notifiable->id],
                // $instance['url'] ?? null,
                $notification->deeplink ?? null,
                $instance['data'] ?? null,
                $instance['buttons'] ?? null,
                $instance['schedule'] ?? null,
                $instance['title'] ?? null,
            );
            // $contents = [ 
            //     "en" => $instance['message'], 
            //  ]; 

            // $params = array(
            //     'app_id' => $appId,
            //     'contents' => $contents,
            //     'api_key' => $apiKey,
            // );
         
            // if (isset($data)) {
            //     $params['data'] = $data;
            // }
         
            // if(isset($headings)){
            //     $params['headings'] = array(
            //         "en" => $headings
            //     );
            // }
         
            OneSignal::sendNotificationCustom($params);
        }
    }
}
