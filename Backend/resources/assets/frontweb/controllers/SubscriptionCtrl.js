angular.module('app.controllers')
.controller('SubscriptionCtrl', function($scope, $http ,SubscriptionApi ,$uibModal ,SharedData ,$state,$rootScope,$window) {
    $('body').attr('class','landing-page-modals subscription-page');
    
    $scope.plans = SharedData._plans;
    $scope._currentPlanDetails = SharedData._currentPlanDetails;
    $scope.subscriptionLogos = ['frontweb/assets/images/subscription/test-the-waters.png', 'frontweb/assets/images/subscription/infinate_gating.png', 'frontweb/assets/images/subscription/subscription.png', 'frontweb/assets/images/subscription/discover.png'];

    $scope.isPurchased = function(plan) {
        if($scope._currentPlanDetails) {
            var allPurchasedPlans = $scope._currentPlanDetails.all_plans;
            
            for(i in allPurchasedPlans) {
                var v = allPurchasedPlans[i];

                if(v.plan_id == plan.id) {
                    return true;
                }
            }
        }
        return false;
    }
    
    $scope.loadFreePlan = function(plan){

    }
     $scope.compare = function() {
        $scope.result = angular.equals($scope.user1, $scope.user2);
    };


    $scope.addSubscriptionModal = function(plan,name){
       // if( SharedData._currentPlanDetails.plan && parseFloat(plan.amount) <= parseFloat(SharedData._currentPlanDetails.plan.price) && plan.stripe_plan_key!="gating2"){
       //      swal({
       //              title: "Oops!",
       //              text : "If you are downgrading, this will take place at the end of your current payment cycle. You can't downgrade plan until "+SharedData._currentPlanDetails.plan.expire_date,
       //                  closeOnConfirm: false,  
       //                  showLoaderOnConfirm: true,
       //                }, function(){
       //                  swal.close();
       //                });
       //              return false;
       //  }
     
        if(!SharedData._user){
            $('#signInModal').modal();
            return false;
        }

        if(SharedData._user.stripe_id && SharedData._user.card_last_four){
            swal({ 
                title: "Subscribe to this plan?",
                showCancelButton: true,
                closeOnConfirm: false,  
                showLoaderOnConfirm: true,
              }, function(){

                var key = SharedData._user.stripe_customer_key ? SharedData._user.stripe_customer_key :'' ;
                SubscriptionApi.subscribeCustomer(null,{'plan': plan,'current':SharedData._currentPlanDetails.plan ? SharedData._currentPlanDetails.plan : '','stripe_customer_key':SharedData._user.stripe_id ? SharedData._user.stripe_id : ''},function(r){ 
                    $scope.isSubmitting = false;
                    if(r.downgrade){
                        var date = new Date(SharedData._currentPlanDetails.plan.expire_date);
                        expire_date = date.getDate() + ' ' + (date.toLocaleString("en-us", { month: "short" })) + ' ' +  date.getFullYear();
                    }

                    swal({ 
                        title: r.downgrade ?"Thank you for your subscription!":'Thank you for upgrading!<br>Enjoy :)',
                        text : r.downgrade ? 'Your request for downgrading to our "'+ r.downgrade.name +'" plan has been recorded.<br>This will take place at the end of your current payment cycle i.e ' +expire_date : '',
                        // type: "info",
                        html:true,
                        closeOnConfirm: false,  
                        showLoaderOnConfirm: true,
                      }, function(){
                        swal.close();
                        $window.location.href = $state.href('app.subscriptions');
                        // if(SharedData._user.user_type == 'artist')
                        //     $window.location.href = $state.href('app.artist.plans');
                      
                      });
                }, function(r){
                    // toastr.error(r.message);  
                    $scope.isSubmitting = false;
                    if(r.data && r.data.message){
                        toastr.error(r.data.message.jsonBody.error.message);
                    }
                    else{
                        toastr.error('Something went wrong.Please try again later.');
                    }
                    swal.close();
                });
                return false; 
           });
        }
        else{

            if(plan.stripe_plan_key == "free"){
                        swal({ 
                            title: "Subscribe to this plan?",
                            // type: "info",
                            showCancelButton: true,
                            closeOnConfirm: false,  
                            showLoaderOnConfirm: true,
                          }, function(){

                            var key = SharedData._user.stripe_customer_key ? SharedData._user.stripe_customer_key :'' ;
                            SubscriptionApi.subscribeCustomer(null,{'plan': plan,'current':SharedData._currentPlanDetails.plan ? SharedData._currentPlanDetails.plan : '','stripe_customer_key':SharedData._user.stripe_id ? SharedData._user.stripe_id : ''},function(r){ 
                                $scope.isSubmitting = false;
                                swal({ 
                                    title: "Thank you for your subscription!",
                                    closeOnConfirm: false,  
                                    showLoaderOnConfirm: true,
                                  }, function(){
                                    swal.close();
                                    $window.location.href = $state.href('app.subscriptions');
                                  //  if(SharedData._user.user_type == 'artist')
                                       // $window.location.href = $state.href('app.artist.plans');
                                   
                                  });
                            }, function(r){
                                // toastr.error(r.message);  
                                $scope.isSubmitting = false;
                                if(r.data && r.data.message){
                                    toastr.error(r.data.message.jsonBody.error.message);
                                }
                                else{
                                    toastr.error('Something went wrong.Please try again later.');
                                }
                                swal.close();
                            });
                            return false; 
                       });
     
            }//if(plan.stripe_plan_key == "free")
            else{
                 $uibModal.open({
                    templateUrl: 'subscription-modal.html',
                    controller: function($scope, $uibModalInstance, parentScopeData , $stateParams ,plan ,stripeKey,years,userName){
                        $scope.cancel = function () {
                            $uibModalInstance.dismiss('cancel');
                        };
                        $scope.years = years;
                        $scope.plan = plan.name;
                        $scope.price = plan.amount;

                        $scope.subscriptions = function (name,coupon) {
                            $scope.isSubmitting = true;
                            $rootScope.showLoading = true;
                            var $form = angular.element('#subscriptionForm');
                            Stripe.setPublishableKey(stripeKey);

                            Stripe.card.createToken($form, stripeResponseHandler = function(status, response){
                                if (response.error) {
                                    $scope.isSubmitting = false;
                                    $rootScope.showLoading = false;
                                    // Show the errors on the form
                                    $scope.$apply(function() {
                                        $scope.serverPaymentResponse = response.error.message;
                                    });
                                }
                                else{
                                    $scope.serverPaymentResponse = '';
                                    var token = response.id;
                                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                                    SubscriptionApi.subscribeCustomer(null,{ 'stripeToken': token, 'plan': plan,'name' : name,'coupon' : coupon ? coupon : '','current':SharedData._currentPlanDetails.plan ? SharedData._currentPlanDetails.plan : '','stripe_customer_key':SharedData._user.stripe_id ? SharedData._user.stripe_id : ''},function(r){ 
                                        $scope.isSubmitting = false;
                                        $rootScope.showLoading = false;
                                        $uibModalInstance.dismiss('cancel');
                                        swal({
                                            title: SharedData._currentPlanDetails.plan && SharedData._currentPlanDetails.plan.plan_id == '1' ? 'Thank you for upgrading!<br>Enjoy :)' : "Thank you for your subscription!",
                                            text : plan.id == 1 ? 'If you are downgrading, this will take place at the end of your current payment cycle.' : '',
                                            closeOnConfirm: false,
                                            html:true,
                                            showLoaderOnConfirm: true
                                        }, function(){
                                            swal.close();
                                            $window.location.href = $state.href('app.subscriptions');
                                          //  if(SharedData._user.user_type == 'artist')
                                               // $window.location.href = $state.href('app.artist.plans');
                                        });
                                    }, function(r){
                                        $scope.isSubmitting = false;
                                        $rootScope.showLoading = false;
                                        if(r.data && r.data.message) {
                                            toastr.error(r.data.message.jsonBody.error.message);
                                        }
                                    });
                                }//else end
                            })//create token
                        };
                        $scope.cancel = function () {
                            $uibModalInstance.dismiss('cancel');
                        };
                    },
                    resolve: {
                        plan: function () {
                            return plan;
                        },
                        userName: function () {
                            var userName = [] ;
                            userName['name'] = SharedData._user.name;
                            userName['first_name'] = SharedData._user.first_name;
                            userName['last_name'] = SharedData._user.last_name;
                            return userName;
                        },
                        stripeKey: function () {
                            return SharedData._stripeKey;
                        },
                        years: function () {
                            return $scope.years;
                        },
                        parentScopeData: function () {
                            return $scope;
                        }
                    }
                });
            }
        }
    }
});