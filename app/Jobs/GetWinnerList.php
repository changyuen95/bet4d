<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;
class GetWinnerList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationTrait;
    protected $draw;
    /**
     * Create a new job instance.
     */
    public function __construct($draw)
    {
        $this->draw = $draw;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $results = $this->draw->results;
        $tickets = $this->draw->tickets()->where('status', Ticket::STATUS['TICKET_COMPLETED'])->get();
        foreach($results as $result){
            foreach($tickets as $ticket){
                $ticketNumbers = $ticket->ticketNumbers;
                foreach($ticketNumbers as $ticketNumber){
                    if($ticketNumber->number == $result->number){
                        $existingWinner = $result->winners()->where('ticket_number_id',$ticketNumber->id)->first();
                        $winner = $result->winners()->updateOrCreate([
                            'ticket_number_id' => $ticketNumber->id,
                        ],[
                            'user_id' => $ticket->user_id,
                            'amount' => mt_rand(1, 9999), //need further calculation for this
                            'outlet_id' => $ticket->outlet_id
                        ]);

                        if(!$existingWinner){
                            $ticketUser = $ticket->user;
                            if($ticketUser){
                                $notificationData = [];
                                $notificationData['title'] = 'Congratulation! You had win the prize';
                                $notificationData['message'] = 'You had win the prize, please wait our staff to distribute the prize to you';
        
                                $this->sendNotification($ticketUser,$notificationData,$winner);
                            }
    
                            $outletStaffs = optional($ticket->outlet)->staffs;
                            foreach($outletStaffs as $outletStaff){
                                $notificationData = [];
                                if($ticketUser){
                                    $notificationData['title'] = $ticketUser->name.' had win the prize';
                                    $notificationData['message'] = $ticketUser->name.' had win the prize, please distribute the prize to customer';
                                }else{
                                    $notificationData['title'] = 'Someone had win the prize';
                                    $notificationData['message'] = 'Someone had win the prize, please distribute the prize to customer';
                                }
        
                                $this->sendNotification($outletStaff,$notificationData,$winner);
                            }
                        }
                    }
                }
            }
        }
    }
}
