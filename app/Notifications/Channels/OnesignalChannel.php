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
                "en" => $instance['message'], 
             ]; 

            $params = array(
                'app_id' => $appId,
                'contents' => $contents,
                'include_player_ids' => self::ulidToUuid($notifiable->id),
                'api_key' => $apiKey,
            );
         
            OneSignal::sendNotificationCustom($params);
        }
    }

    public function ulidToUuid($ulid) {
        // Extract timestamp and randomness components from ULID
        $timestamp = intval(substr($ulid, 0, 10), 16);
        $randomness = substr($ulid, 10);
    
        // Convert timestamp to Unix timestamp (milliseconds since Unix epoch)
        $unixTimestamp = ($timestamp / 1000) + strtotime('1970-01-01');
    
        // Convert Unix timestamp to UUID time component (100 nanoseconds since Gregorian epoch)
        $uuidTime = ($unixTimestamp - strtotime('1582-10-15')) * 10000000;
    
        // Convert randomness component to hexadecimal with padding
        $paddedRandomness = str_pad($randomness, 32, '0', STR_PAD_RIGHT);
    
        // Combine UUID time and randomness to form a UUID
        $uuid = sprintf(
            '%08x-%04x-%04x-%04x-%012s',
            ($uuidTime >> 96) & 0xffffffff,
            ($uuidTime >> 80) & 0xffff,
            ($uuidTime >> 64) & 0xffff,
            ($uuidTime >> 48) & 0xffff,
            $paddedRandomness
        );
    
        // Return the UUID
        return $uuid;
    }
}
