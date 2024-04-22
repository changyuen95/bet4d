<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\Channels\OnesignalChannel;
class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $appId;
    protected $apiKey;
    protected $recipient;
    protected $message;
    protected $module;

    /**
     * Create a new job instance.
     */
    public function __construct($appId, $apiKey, $recipient, $message, $module)
    {
        $this->appId = $appId;
        $this->apiKey = $apiKey;
        $this->recipient = $recipient;
        $this->message = $message;
        $this->module = $module;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = $this->recipient->notifications()->create([
            'title' => $this->message['title'],
            'message' => $this->message['message'],
            'deeplink' => $this->message['deepLink']
        ]);

        if($this->module != null){
            $notification->update([
                'targetable_type' => $this->module->getMorphClass(),
                'targetable_id' => $this->module->id,
            ]);
        }

        OnesignalChannel::send($this->appId, $this->apiKey, $this->recipient,$notification);

    }
}
