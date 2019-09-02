<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WebhookController extends \Laravel\Cashier\Http\Controllers\WebhookController
{

    public function handleWebhook(Request $request)
    {
        return parent::handleWebhook($request);
    }

    /**
     * Handle a Stripe webhook.
     *
     * @param  array  $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded(array $payload)
    {

        $invoice = $payload['data']['object'];
        $customerToken =  $payload['data']['object']['customer'];
        $userInfo = \App\User::where('stripe_id',$customerToken)->select('id')->first(); 

        // $response[$i]['date'] = $invoice->date()->toFormattedDateString();
        $invoiceId = $invoice['id'];
       
        $lines= $invoice['lines']['data'];

       $count = 0;
        if(is_array($lines)){
          $count = count($lines);
          $line = $lines[$count-1];
        }
        else{
          $line = $lines;
        }
        
        $subscriptionId  =$line['id'];
        $amount =  $line['amount'];
       // $response[$i]['description'] =  $line['description'];
        $plans= $line['plan'];
        $plan=  $plans['id'];

        $periods = $line['period'];
        $start_date =  date('Y-m-d H:i:s',$periods['start']);
        $expire_date =  date('Y-m-d H:i:s',$periods['end']);

       $planInfo = \App\Plans::where('stripe_plan_key',$plan)->first();
       $planId = $planInfo->id;

       $userID = $userInfo->id;
       if(!empty($planId) && !empty($userID) && !empty($subscriptionId)){
          $billingObject = \App\PlanBilling::firstOrCreate([
                                                    'plan_id'=>$planId,
                                                    'subscription_id'=>$subscriptionId,
                                                    'start_date' =>$start_date,
                                                    'expire_date' =>$expire_date,
                                                    'user_id' =>$userInfo->id
                                            ]);
           $billingObject->plan_name = $planInfo->name;
           $billingObject->plan_id = $planId;
           $billingObject->subscription_id = $subscriptionId;
           $billingObject->start_date = $start_date;
           $billingObject->expire_date = $expire_date;
           $billingObject->user_id = $userID;

           $billingObject->price = $amount/100;
           $billingObject->discover_demo_limit = $planInfo->discover_demo_limit;
           $billingObject->remix_demo_limit = $planInfo->remix_demo_limit;
           $billingObject->video_demo_limit = $planInfo->video_demo_limit;
           $billingObject->invoice_id = $invoiceId;
           $billingObject->save();
       }
  }


}