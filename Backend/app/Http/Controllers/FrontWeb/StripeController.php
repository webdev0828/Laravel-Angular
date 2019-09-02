<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PlanBilling;
use App\Http\Requests\Auth\UpdateArtistRequest;
use App\Http\Requests\Auth\UpdateProfileImageRequest;

class StripeController extends BaseController {


    public function __construct()
    {
        parent::__construct();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function setupPlans(){

        $plans = \App\Plans::all();
        // print_r($plans);

        foreach($plans as $value){
            \Stripe\Plan::create(array(
            "amount" => $value->amount*100,
            "interval" => "month",
            "name" => ucfirst($value->name),
            "currency" => "GBP",
            "id" => $value->stripe_plan_key)
          );  
         }
    }


    public function index()
    {
        return view('layouts.default'); 
    } 

    public function getPaymentDetails(){
        // \Stripe\Stripe::setApiKey("sk_test_1vpqP9KVCSVTOxniy34jsNV0");
        $user =$this->user;
        if($user && $user->stripe_customer_key){
            $customer = \Stripe\Customer::retrieve($user->stripe_customer_key);
            return response()->json(['status'=>'success','data'=>$customer], 200);
        }        
    }

    public function updatePlanBillingInfo(){
         $user = $this->user;
            $stripeResponse = $user->asStripeCustomer();
            // print_r($stripeResponse);
            // die;
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
    public function downloadInvoice($invoiceId)
    {
        $user = $this->user;
        return $user->downloadInvoice($invoiceId, [
            'vendor'  => 'Sore Thumb Media',
            'product' => 'Sore Thumb Media Monthly Subscription'
        ]);
    }


    public function subscribeCustomer(Request $request){
        $user = $this->user;
        // create the users subscription
        // grab the credit card token
        $ccToken = $request->get('stripeToken');
        $plan = $request->get('plan');
        $coupon = $request->get('coupon');
        $currentPlan = $request->get('current') ? $request->get('current') : '';

        if(isset($currentPlan['price']) && $plan['amount'] < $currentPlan['price']){
            $stripeActions = \App\StripeActions::firstOrNew(['user_id' => $user->id]);
            $stripeActions->user_id = $user->id;
            $stripeActions->actionable_date = $currentPlan['expire_date'];
            $stripeActions->subscription_id = $currentPlan['stripe_id'];
            $stripeActions->stripe_customer_id = $request->get('stripe_customer_key') ? $request->get('stripe_customer_key') : '';
            $stripeActions->action = 'downgrade';
            $stripeActions->change_plan_to = $plan['stripe_plan_key'];
            $stripeActions->save();
            return response()->json(['status'=>'success','downgrade'=>$plan], 200);
        }
         if($coupon)
        {
            try {
                \Stripe\Coupon::retrieve($coupon);
            } catch(\Stripe\Error\InvalidRequest $e) {
                return response()->json(['status'=>'error','message'=>$e], 402);
            }
           
        }

        //  $planInfo = json_decode($plan);
        $plan = $plan['stripe_plan_key'];

        
            $currentPlanInfo = $user->subscriptions->first();

           
            if($user->stripe_id)
            {

                $currentPlanName = $currentPlanInfo->name;
                 // try{
                    
                   
                    if($ccToken){
                        $user->updateCard($ccToken);   
                    }

                    $objectInfo =  $user->subscription($currentPlanName);
                    if($coupon)
                    {
                        $user->applyCoupon($coupon);
                    }
                   

                    $response = $objectInfo->swap($plan);
                    $this->updatePlanBillingInfo();
                 // }catch(Exception $e){
                 //    return response()->json(['status'=>'error','message'=>$e], 402);
                 // }
            }
            else
            {
                try {

                    $subscription = $user->newSubscription('main', $plan);
                    if( $coupon ){
                        $subscription = $subscription->withCoupon($coupon);
                    }

                    $response =  $subscription->create($ccToken, [
                        'email' => $user->email
                        ]);

                    $this->updatePlanBillingInfo();
                } catch(Exception $e) {
                    return response()->json(['status'=>'error','message'=>$e], 402);
                }
            }
        return response()->json(['status'=>'success'], 200);
    }

     public function upgradePlan(){
        $customerKey = ''; //our database customer key came from the database.
        // if(!empty($customerKey)){

        // }

        \Stripe\Subscription::create(array(
          "customer" => "cus_8gXfPZedmp5fuB",
          "plan" => "" //place new plan
        ));
        //get result of this and save in database
    }



    public function updateBillingCard(Request $request){
        // \Stripe\Stripe::setApiKey("sk_test_1vpqP9KVCSVTOxniy34jsNV0");
        $details = $request->get('details');
        $token = $request->get('stripeToken');
        $user = $this->user;
        $billing_info  = array(
                    "First Name" =>  $details['first_name'],
                    "Last Name" =>  $details['last_name'],
                    "Email" => $user->email,
                    "City" => $details['city'],
                    "Country" => $details['country'],
                    "State" => $details['state'],
                    "Zip" =>$details['zip']
                    );

        $metadata = $billing_info ? $billing_info :null ;

        if($user->stripe_customer_key){
            $customerKey = $user->stripe_customer_key;
            $customer = \Stripe\Customer::retrieve($customerKey);
            $customer->metadata = $metadata;
            $customer->source = $token;
            $customer->save();
        }
        else{

            $customer = \Stripe\Customer::create(array(
                  "metadata" => $metadata,
                  "source" =>$token
                  ));

            $user->stripe_customer_key = $customer->id;
            $user->save();
        }

        return response()->json(['status'=>'success'], 200);
    }
   
    public function undoDowngradeSubscription(Request $request){
        $user = $this->user;
        if($user){
            \App\StripeActions::where('user_id',$user->id)->where('action','downgrade')->delete();
        }
        
    }

    public function resumeSubscription(Request $request){
        $user = $this->user;
        $user->subscription('main')->resume();
        \App\StripeActions::where('user_id',$user->id)->delete();
    }
    public function cancelSubscription(Request $request){
        $user = $this->user;
        $user->subscription('main')->cancel();
        //now move move actionable item
        $currentSubscriptions = $user->subscriptions->first();
        $stripeActions = \App\StripeActions::firstOrNew(['user_id' => $user->id]);
        $stripeActions->actionable_date =$currentSubscriptions->ends_at;
        $stripeActions->action ='movefree';
        $stripeActions->stripe_customer_id = $user->stripe_id;
        $stripeActions->subscription_id = $currentSubscriptions->stripe_id;
        $stripeActions->change_plan_to = 'free';
        $stripeActions->save();
    }


    public function addFreePlan(){
        $artist = \App\User::where('user_type','artist')->select('id','email')->get();

        foreach ($artist as $value) {
            $user = \App\User::where('id',$value->id)->first();
            $subscription = $user->newSubscription('main', 'free');
            $response =  $subscription->create(null, [
                'email' => $user->email
                ]);
            $this->addFreePlanInfo($user);
        }
    }
    
    public function addFreePlanInfo($user){
        $user = $user;
        $stripeResponse = $user->asStripeCustomer();
        // print_r($stripeResponse);
        // die;
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