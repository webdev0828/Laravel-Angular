angular.module('app.controllers')

.controller('ArtistCtrl', function($scope, $uibModal ,$http, $rootScope ,$window , $state, ArtistApi, $stateParams, SharedData ,SubscriptionApi ,$timeout,SoundCloudApi,$filter) {
    $('body').attr('class','admin');
    $scope._currentPlanDetails = SharedData._currentPlanDetails;
    $scope.editing = false;
    if($state.params.donate){
        $timeout(function() {
            swal("Thank you for your donation.");
            // swal("Thank You.Donation has been made successfully.");
        }, 1000);
    }

    $scope.getCampaigns = function(){
        $scope.campaigns = [];
        ArtistApi.getCampaign(null, function(r) {
            $scope.campaigns = r.data;
            $scope.scTracks = r.scTracks;
            $('#submitYourDemoModal').modal('show');
        });
    }

    // $scope.getSoundcloudTracks = function() {
    //     $scope.loading = true;
    //     SoundCloudApi.get(null, function(r) {
    //         $scope.loading = false;
    //         $scope.scTracks = r.soundCloudTracks;
    //     },function(r){
    //         $scope.loading = false;
    //         toastr.error(r.data.message);
    //     });
    // };
    $scope.getSoundcloudRemix = function() {
        $scope.loading = true;
        SoundCloudApi.getRemix(null, function(data) {
            $scope.loading = false;
            $scope.soundcloudRemix = data.soundCloudRemix;
        },function(r){
            $scope.loading = false;
            toastr.error(r.data.message);
        });
    };

    $scope.getDemoTracks = function(){
        $scope.allDemoTracks = [];
        ArtistApi.getDemoTracks(null, function(r) {
            $scope.allDemoTracks = r.data;
        });
    }

    $scope.page = 0;
    $scope.lastpage = 1;
    $scope.loading = false;
    $scope.offset = 10 ;

    $scope.graphInit = function(){
        // Campaigns
        $scope.doughnutLabel = [];
        $scope.doughnutChart = [];
        $scope.colors        = [];
        $scope.visitsDates = [];
        $scope.visitsCounts = [];

        // Streamlines
        $scope.slDoughnutLabel = [];
        $scope.slDoughnutChart = [];
        $scope.slColors        = [];
        $scope.slVisitsDates = [];
        $scope.slVisitsCounts = [];

        ArtistApi.getChartData(null, function(r) {
            // Campaigns
            for(var key in r.visits) {
                var date_format = $filter('date')(new Date(key), 'dd-MM-yyyy');
                $scope.visitsDates.push(date_format);
                $scope.visitsCounts.push(r.visits[key]);
            }

            var labelsDate = $scope.visitsDates; //["01/08/2016", "02/08/2016", "03/08/2016", "04/08/2016", "05/08/2016", "06/08/2016", "07/08/2016"];
            var lineChart = $scope.visitsCounts; //[65, 59, 80, 81, 56, 55, 40];
            var lineChartPrevious =[10, 20, 30, 40, 50, 60, 70];

            $scope.lineLabels = labelsDate;
            $scope.lineSeries = ['Series A'];
            $scope.lineChart = [lineChart];
            if(r.count.length){
               shareColors = {'facebook':'#365899','instagram':'#2e5e86','twitter':'#5ea9dd','soundcloud':'#f7540e','youtube':'#cc181e'};
                angular.forEach(r.count, function(value) {
                    $scope.doughnutLabel.push(capitalize(value.share_type));
                    $scope.colors.push(shareColors[value.share_type]);
                    $scope.doughnutChart.push(value.totalCount);
                });
            }

            // Streamlines
            for( var key in r.streamlineVisits ) {
                var date_format = $filter('date')(new Date(key), 'dd-MM-yyyy');

                $scope.slVisitsDates.push(date_format);
                $scope.slVisitsCounts.push(r.streamlineVisits[key]);
            }

            var slLabelsDate = $scope.slVisitsDates;
            var slLineChart = $scope.slVisitsCounts;
            var slLineChartPrevious = [10, 20, 30, 40, 50, 60, 70];

            $scope.slLineLabels = slLabelsDate;
            $scope.slLineSeries = ['Series B'];
            $scope.slLineChart = [slLineChart];

            if (r.streamlineCounts.length) {
                var shareColors = {
                    'spotify':'#2ebd59',
                    'soundcloud':'#f55624',
                    'youtube':'#cb1c27',
                    'apple-Music':'#808080',
                    'tidal':'#000000',
                    'deezer':'#ffff00',
                    'amazon':'#FF9900',
                    'apple-iTune':'#ffc0cb',
                    'googlePlay':'#319FBB',
                    'beatport':'#A8E00F',
                    'bandcamp':'#619AA9',
                    'amazon-mp3':'#79B143',
                    'juno':'#0000ff',
                    'trackitdown':'#ffc0cb',
                    'STM':'#ff0000',
                    'customSocial':'#ffffff'
                };
                angular.forEach(r.streamlineCounts, function(value) {
                    $scope.slDoughnutLabel.push(capitalize(value.share_service));
                    $scope.slColors.push(shareColors[value.share_service]);
                    $scope.slDoughnutChart.push(value.totalCount);
                });
            }
        });

        // $scope.$watch('visitsDates', function(newValue, oldValue){
        //     if (newValue !== oldValue) {
        //         if( $scope.visitsDates) {
        //             $scope.lineLabels = $scope.visitsDates;
        //         }
        //     }
        // });
        // $scope.$watch('visitsCounts', function(newValue, oldValue){
        //     if (newValue !== oldValue) {
        //         if( $scope.visitsCounts) {
        //             $scope.lineLabels = $scope.visitsCounts;
        //         }
        //     }
        // });


        // $scope.onClick = function (points, evt) {
        // };
        // $scope.datasetOverride = [{label: 'Bar chart',borderWidth: 5},];
        $scope.optionsDoughnut = {
                                    responsive: true,
                                    datasetFill : true,
                                    maintainAspectRatio: false,
                                    showTooltips: true,
                                    onAnimationComplete: function () {
                                        this.showTooltip([this.segments[0]], true);
                                    },
                                }

        $scope.optionsLine = {
            responsive: true,
            datasetFill : true,
            maintainAspectRatio: true,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        };
    };
    $scope.graphInit();

    function capitalize(s){
        return s && s[0].toUpperCase() + s.slice(1);
    }

//plans and billing from dashboard

    $scope.currentPage = 1;
    $scope.numPerPage = 10;
    $scope.maxSize = 10;
    $scope.itemsPerPage = 10;

    $scope.getPlansInfo = function(){
        $scope.loadingPlans = true;
        $scope.planInfo = [];
        $scope.masterPlans =[];
        ArtistApi.plans({page:$scope.currentPage}, function(r) {
            $scope.loadingPlans = false;
            $scope.planInfo = r.planBilling.data;
            $scope.masterPlans = r.planBilling.master;
            $scope.currentPage = r.planBilling.current_page;
            $scope.totalplanInfo = r.planBilling.total;

        });
    }
    $scope.$watch('currentPage', function(newValue, oldValue){
        if (newValue !== oldValue) {
            if( $scope.currentPage) {
                $scope.getPlansInfo();
            }
        }
    });


    $scope.getPaymentDetails = function(){
        $scope.billingInfo = [];
        ArtistApi.payment(null, function(r) {

            // $rootScope.paymentDetails = r.data.metadata;
        });
    }

    $scope.updatePaymentDetailsModal = function(){

        $uibModal.open({
            templateUrl: 'update-payment-details-modal.html',
            controller: function($scope, $rootScope  ,$uibModalInstance, parentScopeData , $stateParams ,stripeKey ,years){
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.years = years;
                $scope.details = {};
                $scope.details.first_name = parentScopeData.first_name;
                $scope.details.last_name = parentScopeData.last_name;
                $scope.details.city = parentScopeData.city;
                $scope.details.country = parentScopeData.country;
                $scope.update = function (details) {
                    $rootScope.showLoading = true;
                    $scope.isSubmitting = true;
                    var $form = angular.element('#updatePaymentDetailsForm');

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

                                var token = response.id;
                                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                                SubscriptionApi.updatePaymentDetails(null,{'details': details,'stripeToken':token},function(){
                                $scope.isSubmitting = false;
                                $rootScope.showLoading = false;
                                $uibModalInstance.dismiss('cancel');
                               // toastr.success('Payment details has been updated successfully.');
                                // $uibModalInstance.dismiss('cancel');
                                }, function(r){
                                    $scope.isSubmitting = false;
                                    $rootScope.showLoading = false;
                                    toastr.error('Something went wrong.Please try again.');
                                });
                            }
                        })
                    };
                    $scope.cancel = function () {
                        $uibModalInstance.dismiss('cancel');
                    };
                },
            resolve: {
                Paymentdetails: function () {
                    // return details;
                    // return $rootScope.paymentDetail;
                },
                stripeKey: function () {
                    return SharedData._stripeKey;
                    // return $rootScope.paymentDetail;
                },
                years: function () {
                    return $scope.years;
                    // return $rootScope.paymentDetail;
                },
                parentScopeData: function () {
                    return $rootScope.artist;
                }
            }
        });
    }

    // -- profile actions

    $scope.artist = {};
    $scope.labelgenress={} ;

    $scope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
        var slug = $state.params.slug;
        if(!$state.params.slug){
            slug = SharedData._user.slug;
        }
        // $scope.getActivities(slug);
        if(fromParams.slug != toParams.slug){
            $scope.getArtistProfile(slug);
        }

    });

    $scope.getSlug= function (slug){
        if (SharedData._user) {
            if(slug == SharedData._user.slug && !$state.params.slug){
                $scope.getArtistProfile(slug);
            }
        }
    }

    $scope.getArtistProfile = function(slug){
        var parallaxImage = angular.element('.admin .parallax-mirror img');
        parallaxImage.hide();
        $scope.loading = true;
        if(!slug){
            var slug = SharedData._user.slug;
        }
        $scope.artist = {};
        $scope.error = null;
        ArtistApi.getProfile({slug:slug}, function(r){
            $scope.artist = $rootScope.artist = r.data;

            if ($scope._user) {
                $scope.artist.isEmail = $rootScope.artist.isEmail = $scope._user.isEmail;
            } else {
                $scope.artist.isEmail = $rootScope.artist.isEmail;
            }

            // $scope.artist.genresName = customLib.getColumn($scope.artist.genres, 'name');
            // $scope.allGenres = SharedData._genres;
            $scope.allGenres = SharedData._genresAll;

            $scope.followers = r.data.followers;
            $scope.activities = r.data.activities.data;
            $scope.page = r.data.activities.current_page;
            $scope.lastpage =r.data.activities.last_page;
            $scope.loading = false;

            $scope.artist.oldBio = $scope.artist.bio;
            $scope.artist.city_temp = $scope.artist.city;
            $scope.selectedCountry = $scope.artist.country_code;

            $rootScope.appScope.followingCount = r.followingCount;
            $rootScope.appScope.fanCount = r.fanCount;
            $rootScope.appScope.trackCount = r.trackCount;
            $rootScope.appScope.likeCount = r.likeCount;
            $rootScope.appScope.demoTrackIds = r.demoTrackIds;
            $rootScope.appScope.campaignTrackIds = r.campaignTrackIds;
            $rootScope.appScope.videoReleaseCount = r.videoReleaseCount;
            $rootScope.appScope.subscriptionFeatures = r.subscriptionFeatures;
            parallaxImage.show();
        }, function(r) {
           $scope.error = r;
           $scope.loading = false;
        });
    }

    $scope.loadMoreActivities = function (){

        if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
            $scope.loading = true;
            ArtistApi.getMoreActivities({page: $scope.page+1 , limit : $scope.offset}, null, function(r) {
                for(i in r.data.activities.data) {
                    var v = r.data.activities.data[i];
                    $scope.activities.push(v);
                }
                $scope.page = r.data.activities.current_page;
                $scope.lastpage =r.data.activities.last_page;
                $scope.loading = false;
            }, function(error) {
               $scope.loading = false;
            });
        }
    }

    var limitStep = 3;
    $scope.limit = 3;
    $scope.activitylimit = 3;
    $scope.incrementLimit = function() {
        $scope.limit = $scope.followers.length;
    };
    $scope.decrementLimit = function() {
        $scope.limit = limitStep;
    };
    $scope.incrementactivity = function() {
        $scope.activitylimit = $scope.activities.length;
    };
    $scope.decrementactivity = function() {
        $scope.activitylimit = limitStep;
    };

    $scope.editReset = function(){
        var editBioForm=angular.element('#artist-bio').scope();
        editBioForm.editing  =true ;
        focus('email');
    }
    $scope.updateBio=function(artist){
        $scope.isSubmitting = true;
        ArtistApi.bioUpdate(null,{ 'bio' : artist.bio ,'_token' : $window.globalObj._token},function(r){
            $scope.isSubmitting = false;
            $scope.artist.bio = $scope.artist.oldBio = r.data.bio ;
            // toastr.success('Your information updated successfully.');
        }, function(r){
            $scope.isSubmitting = false;
            $scope.error = r;
        });
    }

    $scope.addDonationModal = function(donate){
        $uibModal.open({
            templateUrl: 'donation-modal.html',
            controller: function($scope, $uibModalInstance, donateData, parentScopeData , $stateParams){

                $scope.artist.paypal_email = parentScopeData['paypal_email'];
                $scope.artist.artist_name = parentScopeData['name'];
                $scope.artist.full_name = parentScopeData['first_name'] + ' '+ parentScopeData['last_name'];
                if(!$scope.artist.full_name){
                    $scope.artist.full_name = parentScopeData['name'];
                }
                $scope.artist.returnRedirect = window.globalObj.baseUrl + '/profile?donate';
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };


                $scope.donate = function (amount) {
                    if(typeof  amount === 'undefined'){
                        $scope.isSubmitting = false;
                        $scope.error =true;
                        return false;
                    }
                    else{
                        $scope.isSubmitting = true;
                        $scope.error =false;
                        return true;
                    }
                };

                // $scope.donation = function (donate) {
                //     $scope.error = false;
                //     $scope.isSubmitting = true;
                //     var $form = angular.element('#donateForm');
                //     Stripe.setPublishableKey('pk_test_gAbZ9z9LZ2LuVpuTO0BkQSIU');

                //     Stripe.card.createToken($form, stripeResponseHandler = function(status, response){

                //         if (response.error) {
                //                 $scope.isSubmitting = false;
                //                 // Show the errors on the form
                //                 $scope.$apply(function() {
                //                     $scope.serverPaymentResponse = response.error.message;
                //                 });
                //             }
                //             else{
                //                 $scope.serverPaymentResponse = '';
                //                 var token = response.id;
                //                 $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                //                 var artist =$stateParams.slug;
                //                 ArtistApi.makeDonation(null,{ 'stripeToken': token, 'amount': donate.amount, 'name': $scope.user.name,'comment': donate.comment , 'artist':artist},function(){
                //                     $scope.isSubmitting = false;
                //                     toastr.success('Donation has been made successfully.');
                //                     $uibModalInstance.dismiss('cancel');
                //                 }, function(){
                //                     $scope.isSubmitting = false;
                //                     toastr.error('Something went wrong.Please try again.');
                //                 });
                //             }
                //         })

                // };
                $scope.cancel = function () {
                        $uibModalInstance.dismiss('cancel');
                };
            },
            resolve: {
                donateData: function () {
                    // return angular.copy(donateData);
                },
                parentScopeData: function () {
                    var artist = [] ;
                    artist['name'] = $scope.artist.name;
                    artist['first_name'] = $scope.artist.first_name;
                    artist['last_name'] = $scope.artist.last_name;
                    artist['paypal_email'] = $scope.artist.paypal_email;
                    return artist;
                }
            }
        });
    }

    // $scope.closeModal = function(){

    //     $('#submitYourDemoModal').modal('hide');
    //     $state.go('app.subscriptions');
    // }
    $scope.submitDemo = function (track,type) {


        if(type == 'discover' && SharedData._currentPlanDetails.plan.discover_demo_limit - SharedData._currentPlanDetails.demoCount <= 0 ){
            return false;
        }
        if(type == 'remix' && SharedData._currentPlanDetails.plan.remix_demo_limit - SharedData._currentPlanDetails.remixCount <= 0 ){
            return false;
        }
        if(type == 'video' && SharedData._currentPlanDetails.plan.video_demo_limit - SharedData._currentPlanDetails.videoCount <= 0 ){
            return false;
        }

        var parentScope=angular.element('#dashboard_ctrl').scope();
        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var data = ArtistApi.submitDemos(null, { 'slug' : track.slug , 'type': type,'_token' : $window.globalObj._token,'source':'local'},  function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            if(angular.isDefined(parentScope)){
                // parentScope.demoTracks.unshift(r.data);
                parentScope.getTracksData();
            }
            if(type == 'discover'){
                SharedData._currentPlanDetails.demoCount = SharedData._currentPlanDetails.demoCount + 1;
            }
            if(type == 'remix'){
                SharedData._currentPlanDetails.remixCount = SharedData._currentPlanDetails.remixCount + 1;
            }
            if(type == 'video'){
                SharedData._currentPlanDetails.videoCount = SharedData._currentPlanDetails.videoCount + 1;
            }

            $scope.getNotifications();
            $('#submitYourDemoModal').modal('hide');
            //toastr.success('Your demo track sent successfully.');
        }, function(r) {
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
        });
    }

    $scope.reset = function(){
        var submitDemo=angular.element('#submitYourDemoModal').scope();
        submitDemo.track='';
        $('#submitYourDemoModal input[type=checkbox]').attr('checked',false);
        submitDemo.termCheck = submitDemo.demoTermCheck = submitDemo.demoTermCheck1 = submitDemo.demoTermCheck2 = submitDemo.demoTermCheck3 = submitDemo.demoTermCheck4 = submitDemo.demoTermCheck5 = submitDemo.demoTermCheck6 = 0 ;
        if(submitDemo){
            // $("#submitDemoForm")[0].reset();
            submitDemo.demo={};
            if(submitDemo.submitDemoForm){
                submitDemo.submitDemoForm.$setPristine();
                submitDemo.submitDemoForm.$setUntouched();
            }
            if(submitDemo.DemoForm){
                submitDemo.DemoForm.$setPristine();
                submitDemo.DemoForm.$setUntouched();
            }
            if(submitDemo.submitVideoForm){
                submitDemo.submitVideoForm.$setPristine();
                submitDemo.submitVideoForm.$setUntouched();
            }
        }
    }


    $scope.submitRepost = function (track, type) {

        if(type == 'discover' && SharedData._currentPlanDetails.plan.discover_demo_limit - SharedData._currentPlanDetails.demoCount <= 0 ){
            return false;
        }
        if(type == 'remix' && SharedData._currentPlanDetails.plan.remix_demo_limit - SharedData._currentPlanDetails.remixCount <= 0 ){
            return false;
        }
        if(type == 'video' && SharedData._currentPlanDetails.plan.video_demo_limit - SharedData._currentPlanDetails.videoCount <= 0 ){
            return false;
        }
        var parentScope=angular.element('#dashboard_ctrl').scope();
        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var data = ArtistApi.submitRepost(null, {'slug' : track.slug, '_token' : $window.globalObj._token},  function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            if(angular.isDefined(parentScope)){
                parentScope.demoTracks.unshift(r.data);
            }
            if(type == 'discover'){
                SharedData._currentPlanDetails.demoCount = SharedData._currentPlanDetails.demoCount + 1;
            }
            if(type == 'remix'){
                SharedData._currentPlanDetails.remixCount = SharedData._currentPlanDetails.remixCount + 1;
            }
            if(type == 'video'){
                SharedData._currentPlanDetails.videoCount = SharedData._currentPlanDetails.videoCount + 1;
            }
            parentScope.getTracksData();
            $('#repostModal').modal('hide');
            // toastr.success('Your demo track is repost successfully.');
        }, function(r) {
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
        });
    }

    $scope.resetRepostForm = function(){
        var submitRepost=angular.element('#repostModal').scope();
        submitRepost.track='';
        $('#repostModal input[type=checkbox]').attr('checked',false);
        submitRepost.termCheck = submitRepost.termCheck = submitRepost.termCheck1 = submitRepost.termCheck2 = submitRepost.termCheck3 = submitRepost.termCheck4 = submitRepost.termCheck5 = submitRepost.termCheck6 = 0 ;
        if(submitRepost){
            // $("#submitDemoForm")[0].reset();
            submitRepost.demo={};
            if(submitRepost.submitDemoForm){
                submitRepost.submitDemoForm.$setPristine();
                submitRepost.submitDemoForm.$setUntouched();
            }
            if(submitRepost.submitRemixTrackForm){
                submitRepost.submitRemixTrackForm.$setPristine();
                submitRepost.submitRemixTrackForm.$setUntouched();
            }

            if(submitRepost.submitVideoForm){
                submitRepost.submitVideoForm.$setPristine();
                submitRepost.submitVideoForm.$setUntouched();
            }
        }
    }


    // $scope.getActivities = function(){
    //     var slug = $state.params.slug;
    //     ArtistApi.getActivities(null, {'slug':slug}, function(r) {
    //         $scope.allActivities = r.data;
    //     });
    // }

    $scope.cropImgObj = null;
    $scope.croppedImage = null;
    $scope.imageType = "";
    $rootScope.cropImageModal = function(avatarFile, imageType) {
        $('.crop-wrap').empty();

        $scope.imageType = imageType.split(".")[1];
        if ($scope.imageType == "profile" || $scope.imageType == "profilePic") {
            $('.crop-note').addClass('hidden');
        } else {
            $('.crop-note').removeClass('hidden');
        }

        $("#cropImageModal").one('shown.bs.modal', function() {
            var reader = new FileReader();
            reader.onload = function (e) {
                if ($scope.imageType == "profile" || $scope.imageType == "profilePic") {
                    $scope.cropImgObj = new Croppie($('.crop-wrap')[0], {
                        viewport: {
                            width: 568,
                            height: 568,
                            type: 'circle'
                        },
                        boundary: {
                            width: 568,
                            height: 568
                        },
                        mouseWheelZoom: false,
                        update: function() {
                            $scope.cropImgObj.result('base64').then(function(img) {
                                $scope.$apply(function() {
                                    $scope.croppedImage = img;
                                });
                            });
                        }
                    });
                } else if ($scope.imageType == "banner" || $scope.imageType == "update_banner") {
                    $scope.cropImgObj = new Croppie($('.crop-wrap')[0], {
                        viewport: {
                            width: 568,
                            height: 248,
                            type: 'square'
                        },
                        boundary: {
                            width: 568,
                            height: 248
                        },
                        mouseWheelZoom: false,
                        update: function() {
                            $scope.cropImgObj.result('base64').then(function(img) {
                                $scope.$apply(function() {
                                    $scope.croppedImage = img;
                                });
                            });
                        }
                    });
                }

                $scope.cropImgObj.bind({
                    url: e.target.result
                });
            }
            reader.readAsDataURL(avatarFile);
        }).modal('show');
    }

    $rootScope.updateCroppedImage = function() {
        if ($scope.imageType == 'profile' || $scope.imageType == 'profilePic') {
            $rootScope.updateProfileCroppedImage();
        } else if ($scope.imageType == 'banner' || $scope.imageType == 'update_banner') {
            $rootScope.updateBannerCroppedImage();
        }
    }
    
    $rootScope.updateProfileCroppedImage = function() {
        var fd = new FormData();
        fd.append('file', $scope.croppedImage);
        fd.append('_token', $window.globalObj._token);
        $rootScope.showLoading = true;
        ArtistApi.updateImage(null, fd, function(r){
            // toastr.success('Profile image updated successfully.');
            $scope.artist.avatar = $rootScope.user.avatar = r.data.avatar ;
            $rootScope.showLoading = false;
            $("#cropImageModal").modal('hide');
        }, function(r){
            toastr.error('Something went wrong. Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $rootScope.updateProfileImage = function(avatarFile){
        var fd = new FormData();
        fd.append('file', avatarFile);
        fd.append('_token', $window.globalObj._token);
        $rootScope.showLoading = true;
        ArtistApi.updateImage(null, fd, function(r){
            // toastr.success('Profile image updated successfully.');
            $scope.artist.avatar = $rootScope.user.avatar = r.data.avatar ;
            $rootScope.showLoading = false;
        }, function(r){
            toastr.error('Something went wrong. Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $rootScope.updateBannerCroppedImage = function(){
        var fd = new FormData();
        fd.append('file', $scope.croppedImage);
        fd.append('_token', $window.globalObj._token);
        $rootScope.showLoading = true;
        ArtistApi.bannerUpdate(null,fd,function(r){
            // toastr.success('Banner image updated successfully.');
            $scope.artist.cover = r.data.cover;
            $rootScope.showLoading = false;
            $("#cropImageModal").modal('hide');
        }, function(r){
            // $scope.error = r.message;
            // toastr.error($scope.error);
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $rootScope.updateBannerImage = function(avatarFile){
        var fd = new FormData();
        fd.append('file', avatarFile);
        fd.append('_token', $window.globalObj._token);
        $rootScope.showLoading = true;
        ArtistApi.bannerUpdate(null,fd,function(r){
            // toastr.success('Banner image updated successfully.');
            $scope.artist.cover = r.data.cover;
            $rootScope.showLoading = false;
        }, function(r){
            // $scope.error = r.message;
            // toastr.error($scope.error);
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $scope.imagePreview = function(path, name){

        $rootScope.artistProfileImage = path;
        $rootScope.artistName = name;
        $('#imagePreview').modal();
    }

    // $scope.cancelSubscriptions = function(subscription_id) {
    //     swal({
    //             title :'Are you sure, you want to cancel your subscription?',
    //             text: "You are currently price locked, if you cancel now you will lose out on your current deal.<br>(prices will increase over time as the platform grows)",
    //             // type: "",
    //             html: true,
    //             showCancelButton: true,
    //             closeOnConfirm: true,
    //             showLoaderOnConfirm: false
    //         }, function(){
    //             $rootScope.showLoading = true;
    //             SubscriptionApi.cancelSubscription({subscription_id : subscription_id}, function(data) {
    //                 $scope._currentPlanDetails.plan.isCancel = 1;
    //             // toastr.success('',data.success);
    //                 $rootScope.showLoading = false;
    //             });
    //     })
    // };

});