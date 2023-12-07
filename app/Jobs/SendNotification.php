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
    protected $recipient;
    protected $message;
    protected $module;

    /**
     * Create a new job instance.
     */
    public function __construct($recipient, $message, $module)
    {
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
        ]);

        if($this->module != null){
            $notification->update([
                'targetable_type' => $this->module->getMorphClass(),
                'targetable_id' => $this->module->id,
            ]);
        }

        OnesignalChannel::send($this->recipient,$notification);

    }
}
