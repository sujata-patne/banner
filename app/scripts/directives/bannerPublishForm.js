directives.directive('bannerPublishForm', ['$appLocation','Store','User','Client','Campaign','alertService','$location','$route','Banner','$rootScope','BannerPublish','$q', function ($appLocation,Store,User,Client,Campaign,alertService,$location,$route,Banner,$rootScope,BannerPublish,$q) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/banner_publish_form'),
            scope:{
                id:"=",
                edit:"=",
                publishId:"="
            },
            controller: function ($scope)
            {
                $scope.banner = {}; 
                $scope.bannerPublish ={};
                $scope.operatorList = [];

                $scope.requests = []; 

                
               
                 $scope.requests[0] =   Banner.getOperatorsList(function(res){
                    $scope.operatorList = res.data.message.operatorDetails.items;
                    $scope.bannerPublish.operator = angular.copy( res.data.message.operatorDetails.items);                    
                });
                
                $scope.requests[1] = Banner.getCircleList(function(res){                    
                    $scope.circleList = res.data.message.circleDetails.items;
                    $scope.bannerPublish.circle = res.data.message.circleDetails.items;
                });

                $scope.requests[2] = Banner.getHandsetList({},function(res){                    
                    $scope.handsetList = res.data.message.handsetDetails.items;
                    $scope.bannerPublish.handset = res.data.message.handsetDetails.items;
                });

                $scope.requests[3] = Banner.getOperatingSystemList(function(res){                    
                    $scope.operatingSystemList = res.data.message.operatingSystemDetails.items;
                    $scope.bannerPublish.operatingSystem = res.data.message.operatingSystemDetails.items;
                });

                $scope.requests[4] = Banner.getHandsetGroup(function(res){                    
                    $scope.handsetGroupList = res.data.message.handsetGroupDetails.items;
                    $scope.bannerPublish.handset_group = res.data.message.handsetGroupDetails.items;
                });

                $scope.requests[5] = Banner.getOperatingSystemList(function(res){                    
                    $scope.operatingSystemList = res.data.message.operatingSystemDetails.items;
                    $scope.bannerPublish.operatingSystem = res.data.message.operatingSystemDetails.items;
                });

                $scope.requests[6] =   Banner.getTimeslotList(function(res){
                    $scope.timeslotList = res.data.message.timeslotDetails.items;
                    $scope.bannerPublish.timeslot = angular.copy( res.data.message.timeslotDetails.items);                    
                    $scope.bannerPublish.timeslot = $scope.timeslotList;
                });
                
            $q.all($scope.requests).then(function(res)
            {
                console.log('last'); 
          
                    if( typeof $scope.id != 'undefined')
                    {
                        Store.getStoresByCampaign({banner_id: $scope.id}, function (res) {

                        $scope.storeList = res.data.message.storeData;

                        
                                Banner.findOne({id: $scope.id}, function (res)
                                {
                                    if (res.data.message.status == 'success')
                                    {
                                        $scope.banner = res.data.message.clientsDetails;
                                        
                                        $scope.banner.name = $scope.banner.banner_name;
                                        $scope.banner.clients = {id: $scope.banner.client_id};
                                        
                                        
                                        if($scope.banner.bannerPublish != null )
                                        {
                                            $scope.bannerPublish = $scope.banner.bannerPublish;
                                            $scope.bannerPublish.circle = $scope.bannerPublish.selectedCircles
                                            $scope.bannerPublish.operator = $scope.bannerPublish.selectedOperators;
                                            $scope.bannerPublish.timeslot = $scope.banner.timeslotsSelected;
                                        }
                                        else
                                        {
                                            $scope.bannerPublish.operator = $scope.operatorList;
                                        }
                                        
                                        $scope.bannerPublish.site = {st_id:parseInt( $scope.bannerPublish.store_id)}; 
                                        
                                        $scope.siteChange();
                                        
                                        $scope.bannerPublish.page = {pp_id:parseInt( $scope.bannerPublish.icn_pub_page_pp_id)}; 

                                        $scope.pageChange();
                                        
                                        $scope.bannerPublish.place = {ppp_id:parseInt( $scope.bannerPublish.icn_pub_page_portlet_ppp_id)}; 
                                        
                                        

                                       
                                                                                
                                        $scope.bannerPublish.deviceBroser = 'group';
                                        
                                        Campaign.getDeliveryChannel($scope.banner.clients, function (res) {

                                            $scope.storesList = res.data.message.clientsDetails.stores;

                                            $scope.banner.channels = {cd_id: $scope.banner.catalogue_detail_cd_id};

                                            Campaign.findAllByClient($scope.banner, function (res) {
                                                
                                                $scope.banner.scenario = "update";


                                                if(!$scope.edit)
                                                {
                                                    $scope.bannerPublish = {scenario:'insert'};
                                                    $scope.bannerPublish.start_date = $scope.banner.start_date;
                                                    $scope.bannerPublish.end_date = $scope.banner.campaignDetails.end_date;
                                                    
                                                    
                                                    $scope.bannerPublish.user_type = 2; 
                                                    $scope.bannerPublish.banner_id = $scope.id; 
                                                    $scope.bannerPublish.id = $scope.publishedId; 
                                                    $scope.bannerPublish.deviceBroser = 'group';
                                                   // $scope.bannerPublish.operator = $scope.operatorList;
                                                  //  $scope.bannerPublish.circle = $scope.circleList;
                                                }
                                                else
                                                {
                                                    $scope.bannerPublish.scenario ='update';
                                                    $scope.bannerPublish.banner_id = $scope.id; 
                                                    $scope.bannerPublish.id = $scope.publishId; 
                                                    

                                                    
                                                    $scope.bannerPublish.handset_group = {chgr_group_id:$scope.bannerPublish.content_handset_group_id};
                                                    
                                                    if($scope.bannerPublish.content_handset_group_id == null)
                                                    {
                                                        $scope.bannerPublish.deviceBroser ='handset';
                                                    }
                                                    
                                                }                 
                                            });
                                        });
                                    }
                                });
                                
                        });

                    }
            }); 
            
                
                $scope.save = function(frm)
                {
                    frm.submitted = true; 
                 
                    if(frm.$valid)
                    {
                        
                        BannerPublish.save($scope.bannerPublish,function(res){
                            
                            if(res.data.message.status == 'success')
                            {
                                $scope.bannerPublish = {};
                                frm.$setPristine();
                                frm.submitted = false;                             
                                alertService.add(res.data.message.status, res.data.message.msg);
                                $location.url('banner-list?alert=true');
                 
                            }
                            else
                            {
                                
                                alertService.add(res.data.message.status, res.data.message.msg);
                            
                            }
                            
                        });
                        
                    }
                    
                    
                };
                
                $scope.pageList = [];
                
                $scope.siteChange = function()
                {
                    if( typeof $scope.bannerPublish.site !== 'undefined' )
                    {
                        Banner.getPagesBySite($scope.bannerPublish.site,function(res){

                           if(res.data.message.status =='success')
                           {
                                $scope.pageList = res.data.message.pageDetails.items;  
                            }
                        });
                    }
                    else
                    {
                        $scope.pageList = [];
                    }
                    
                };
                
                
                $scope.placeList = [];
                $scope.pageChange = function()
                {                    
                    if( typeof $scope.bannerPublish.page !== 'undefined' )
                    {
                        Banner.getPlacesByPage($scope.bannerPublish.page,function(res){

                           if(res.data.message.status =='success')
                           {
                                $scope.placeList = res.data.message.placeDetails.items;  
                                $scope.bannerPublish.place =  $scope.bannerPublish.place; 
                            }
                        });
                    }
                    else
                    {
                       $scope.placeList = [];
                    }
                };
                
                
                
                $scope.reset = function (form)
                {
                    form.$setPristine();
                    form.submitted = false;
                    $scope.bannerPublish.scenario = 'insert';
                    alertService.reset();
                    $route.reload();
                };


                $scope.remove = function()
                {
                    
                    var confirmMsg = confirm("Are you sure you want to remove mapping between banner and place holder ? ");
                    

                    if(confirmMsg)
                    {
                        Banner.removePublishedBanner({id:$scope.bannerPublish.id,banner_id:$scope.bannerPublish.banner_id},function(res){

                            if(res.data.message.status == 'success')
                            {

                                alertService.add(res.data.message.status, res.data.message.msg);
                                $location.url('banner-list?alert=true');
                            }

                        });
                    }
                    
                }
                
                
                
            }
        };
    }]);