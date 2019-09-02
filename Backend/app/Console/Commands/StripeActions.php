<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StripeActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:downgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downgrade subscription';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $now = date('Y-m-d');
        $stripeActions = \App\StripeActions::whereRaw('DATE_FORMAT(actionable_date, "%Y-%m-%d") = "'.$now.'" ')
                                         ->where('action','downgrade')
                                         ->select('id','stripe_customer_id','user_id','subscription_id','change_plan_to')
                                         ->get();
        
        if($stripeActions){
            foreach ($stripeActions as $value) {
                $user = \App\User::find($value['user_id']);
                $plan = $value['change_plan_to'];
                $currentPlanInfo = $user->subscriptions->first();
                if($user->stripe_id)
                {
                    $currentPlanName = $currentPlanInfo->name;
                     try{
                        $objectInfo =  $user->subscription($currentPlanName);
                        $response = $objectInfo->swap($plan);
                        $this->updatePlanBillingInfo($user);
                     }catch(Exception $e){
                        $this->info("Exception");
                     }
                }
                $value->delete();
            }
        }
    }

    public function updatePlanBillingInfo($user){
            $user = $user;
            $stripeResponse = $user->asStripeCustomer();
            if($stripeResponse){
                $stripeResponse = $stripeResponse['subscriptions']['data'][0];
                $planInfo = \App\Plans::where('stripe_plan_key',$stripeResponse['plan']['id'])->first();

                $billingObject = new \App\PlanBilling;
                $billingObject->user_id = $user->id;
                $billingObject->plan_id = $planInfo->id;
                $billingObject->plan_name = $planInfo->name;
                
                $billingObject->price = $planInfo->amount;
                $billingObject->start_date = date('Y-m-d H:i:s',$stripeResponse->current_period_start);
                $billingObject->expire_date = date('Y-m-d H:i:s',$stripeResponse->current_period_end);
                $billingObject->discover_demo_limit = $planInfo->discover_demo_limit;
                $billingObject->remix_demo_limit = $planInfo->remix_demo_limit;
                $billingObject->video_demo_limit = $planInfo->video_demo_limit;
                $billingObject->subscription_id = $stripeResponse->id;
                $billingObject->save();

             }
    }

}
