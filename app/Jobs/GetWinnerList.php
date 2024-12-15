<?php

namespace App\Jobs;

use App\Models\DrawResult;
use App\Models\PotentialWinningPriceList;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketNumber;
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
                    if($ticketNumber->number == $result->number && ($ticketNumber->type == TicketNumber::TYPE['Straight'] || $ticketNumber->type == TicketNumber::TYPE['Box'])){
                        $existingWinner = $result->winners()->where('ticket_number_id',$ticketNumber->id)->first();
                        $prizeAmount = $this->calculatePrize($result,$ticketNumber);
                        $winner = $result->winners()->updateOrCreate([
                            'ticket_number_id' => $ticketNumber->id,
                        ],[
                            'user_id' => $ticket->user_id,
                            'amount' => $prizeAmount, //need further calculation for this
                            'outlet_id' => $ticket->outlet_id
                        ]);

                        if(!$existingWinner){
                            $ticketUser = $ticket->user;
                            if($ticketUser){
                                $notificationData = [];
                                $notificationData['title'] = 'Congratulation! You had win the prize';
                                $notificationData['message'] = 'You had win the prize, please wait our staff to distribute the prize to you';
                                $notificationData['deepLink'] = 'fortknox://me/winner/'.$winner->id;
                                $appId = env('ONESIGNAL_APP_ID');
                                $apiKey = env('ONESIGNAL_REST_API_KEY');
                                $this->sendNotification($appId, $apiKey, $ticketUser,$notificationData,$winner);
                            }
    
                            $outletStaffs = optional(optional($ticket->outlet)->staffs())->whereHas('roles', function($q) {
                                return $q->where('name', Role::OPERATOR);
                            })->get();
                            foreach($outletStaffs as $outletStaff){
                                $notificationData = [];
                                if($ticketUser){
                                    $notificationData['title'] = $ticketUser->name.' had win the prize';
                                    $notificationData['message'] = $ticketUser->name.' had win the prize, please distribute the prize to customer';
                                }else{
                                    $notificationData['title'] = 'Someone had win the prize';
                                    $notificationData['message'] = 'Someone had win the prize, please distribute the prize to customer';
                                }
                                $notificationData['deepLink'] = 'fortknox-admin://distribute-prizes/'.$winner->id;
                                $appId = env('ONESIGNAL_STAFF_APP_ID');
                                $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');
                                $this->sendNotification($appId, $apiKey, $outletStaff,$notificationData,$winner);
                            }
                        }
                    }elseif($ticketNumber->type == TicketNumber::TYPE['e-box']){
                        $ticketNumberArray = $this->getPermutationsProbabilities($result->number);
                        foreach($ticketNumberArray as $resultEbox){
                            if($resultEbox == $ticketNumber->number){
                                $existingWinner = $result->winners()->where('ticket_number_id',$ticketNumber->id)->where('draw_result_id',$result->id)->first();
                                $prizeAmount = $this->calculateEboxPrize($result,$ticketNumber);
                                $winner = $result->winners()->updateOrCreate([
                                    'ticket_number_id' => $ticketNumber->id,
                                ],[
                                    'user_id' => $ticket->user_id,
                                    'amount' => $prizeAmount, //need further calculation for this
                                    'outlet_id' => $ticket->outlet_id
                                ]);

                                if(!$existingWinner){
                                    $ticketUser = $ticket->user;
                                    if($ticketUser){
                                        $notificationData = [];
                                        $notificationData['title'] = 'Congratulation! You had win the prize';
                                        $notificationData['message'] = 'You had win the prize, please wait our staff to distribute the prize to you';
                                        $notificationData['deepLink'] = 'fortknox://me/winner/'.$winner->id;
                                        $appId = env('ONESIGNAL_APP_ID');
                                        $apiKey = env('ONESIGNAL_REST_API_KEY');
                                        $this->sendNotification($appId, $apiKey, $ticketUser,$notificationData,$winner);
                                    }
            
                                    $outletStaffs = optional(optional($ticket->outlet)->staffs())->whereHas('roles', function($q) {
                                        return $q->where('name', Role::OPERATOR);
                                    })->get();
                                    foreach($outletStaffs as $outletStaff){
                                        $notificationData = [];
                                        if($ticketUser){
                                            $notificationData['title'] = $ticketUser->name.' had win the prize';
                                            $notificationData['message'] = $ticketUser->name.' had win the prize, please distribute the prize to customer';
                                        }else{
                                            $notificationData['title'] = 'Someone had win the prize';
                                            $notificationData['message'] = 'Someone had win the prize, please distribute the prize to customer';
                                        }
                                        $notificationData['deepLink'] = 'fortknox-admin://distribute-prizes/'.$winner->id;
                                        $appId = env('ONESIGNAL_STAFF_APP_ID');
                                        $apiKey = env('ONESIGNAL_STAFF_REST_API_KEY');
                                        $this->sendNotification($appId, $apiKey, $outletStaff,$notificationData,$winner);
                                    }
                                }
                            }
                        }
                        
                    }
                }
            }
        }
    }

    public function calculatePrize($result,$ticketNumber){
        $amount = 0;
        $potentialWinningData = PotentialWinningPriceList::where('type',$ticketNumber->type)->first();
        $bigAmount = $ticketNumber->big_amount;
        $smallAmount = $ticketNumber->small_amount;

        if($result->type == DrawResult::TYPE['1st']){
            $amount = ($bigAmount * $potentialWinningData->big1st) + ($smallAmount * $potentialWinningData->small1st);
        }elseif($result->type == DrawResult::TYPE['2nd']){
            $amount = ($bigAmount * $potentialWinningData->big2nd) + ($smallAmount * $potentialWinningData->small2nd);
        }elseif($result->type == DrawResult::TYPE['3rd']){
            $amount = ($bigAmount * $potentialWinningData->big3rd) + ($smallAmount * $potentialWinningData->small3rd);
        }elseif($result->type == DrawResult::TYPE['special']){
            $amount = ($bigAmount * $potentialWinningData->big_special);
        }elseif($result->type == DrawResult::TYPE['consolation']){
            $amount = ($bigAmount * $potentialWinningData->big_consolation);
        }

        return $amount;
    }

    public function calculateEBoxPrize($result,$ticketNumber){
        $amount = 0;
        $potentialWinningData = PotentialWinningPriceList::where('remark',$ticketNumber->permutation_type.' '.ucfirst($ticketNumber->type))->first();
        $bigAmount = $ticketNumber->big_amount;
        $smallAmount = $ticketNumber->small_amount;

        if($result->type == DrawResult::TYPE['1st']){
            $amount = ($bigAmount * $potentialWinningData->big1st) + ($smallAmount * $potentialWinningData->small1st);
        }elseif($result->type == DrawResult::TYPE['2nd']){
            $amount = ($bigAmount * $potentialWinningData->big2nd) + ($smallAmount * $potentialWinningData->small2nd);
        }elseif($result->type == DrawResult::TYPE['3rd']){
            $amount = ($bigAmount * $potentialWinningData->big3rd) + ($smallAmount * $potentialWinningData->small3rd);
        }elseif($result->type == DrawResult::TYPE['special']){
            $amount = ($bigAmount * $potentialWinningData->big_special);
        }elseif($result->type == DrawResult::TYPE['consolation']){
            $amount = ($bigAmount * $potentialWinningData->big_consolation);
        }

        return $amount;
    }

    public function getPermutationsProbabilities($number)
    {
        // Convert the string number into an array of digits
        $digits = str_split($number);
    
        // Generate all permutations
        $permutations = $this->generatePermutations($digits);
    
        // Convert each permutation from array to string
        $permutationStrings = array_map(function ($permutation) {
            return implode('', $permutation);
        }, $permutations);
    
        // Remove duplicate permutations (if any)
        $uniquePermutations = array_unique($permutationStrings);
    
        // Return the result as a regular array (unique permutations)
        return $uniquePermutations;
    }
    
    // Function to generate all permutations of an array
    private function generatePermutations(array $array)
    {
        $result = [];
    
        // If there is only one element, return it as the only permutation
        if (count($array) == 1) {
            return [$array];
        }
    
        // Loop through each element in the array
        foreach ($array as $key => $value) {
            // Remove the current element from the array
            $remaining = $array;
            unset($remaining[$key]);
            // Recursively get permutations of the remaining elements
            $permutations = $this->generatePermutations(array_values($remaining));
    
            // Merge the current element with all the permutations of the remaining elements
            foreach ($permutations as $permutation) {
                $result[] = array_merge([$value], $permutation);
            }
        }
    
        return $result;
    }
}
