<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;
class GetWinnerList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
                        $result->winners()->updateOrCreate([
                            'ticket_number_id' => $ticketNumber->id,
                        ],[
                            'user_id' => $ticket->user_id,
                            'amount' => mt_rand(1, 9999) //need further calculation for this
                        ]);
                    }
                }
            }
        }
    }
}
