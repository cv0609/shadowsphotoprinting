<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Affiliate;
use App\Models\User;
use App\Models\AffiliateSale;

use Illuminate\Support\Facades\Log;


class AwardReferralPoints
{

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        $user = $event->order->user;
    

        Log::info('OrderPlaced event triggered', [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'total' => $order->total,
            'created_at' => $order->created_at,
        ]);

        if (!$user->referred_by) return;
    
        $ambassador = $user->ambassador;
    

        // Ensure they are approved
        if (!$ambassador || !$ambassador->is_approved || !$ambassador->updated_at) {
            return;
        }
    
        // Only apply if the current order is placed *after* approval
        if ($event->order->created_at <= $ambassador->updated_at) {
            return;
        }
    
        // // Check if the user placed an order before they became an ambassador
        // $placedBefore = $user->orders()
        //     ->where('created_at', '<', $ambassador->approved_at)
        //     ->exists();
    
        // if (!$placedBefore) {
        //     return; // Must have placed at least one order *before* becoming ambassador
        // }
    
        // ✅ All conditions met — reward referrer
       // $affiliate = Affiliate::where('referral_code',$user->referred_by)->first();

        if ($user->referred_by) {
            $referrer = User::find($user->referred_by);
        
            if ($referrer && $referrer->affiliate) {
                $referrer->affiliate->increment('commission', 5);

            // Optional: log referral reward
            AffiliateSale::create([
                'affiliate_id'=>$referrer->affiliate->id,
                'order_id'=>$event->order->id,
                'commission'=>5,
                'shutter_points'=>500,
                'operation'=>'plus',
                'reason' => 'Referral: Referred user became ambassador and placed post-approval order.',
            ]);

            } else {
                \Log::warning('Referrer or affiliate relationship missing for user ID: ' . $user->id);
            }
        }

    }
    
}
