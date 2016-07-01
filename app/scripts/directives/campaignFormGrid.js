directives.directive('campaignFormGrid', ['$appLocation','Store','User','Client','Campaign','alertService','$location','$route', function ($appLocation,Store,User,Client,Campaign,alertService,$location,$route) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/campaign_form_grid'),
            controller: function ($scope)
            {

                $scope.priorities = [
                    {name:"Very Low",value:1},
                    {name:"Low",value:2},
                    {name:"Medium",value:3},
                    {name:"High",value:4},
                    {name:"Very High",value:5}
                ];


            $scope.grid = {};
            $scope.campaign = {};
            
            $scope.campaign.priority = {value:3};
            
            if(typeof $scope.id != 'undefined' && $scope.id != null)
                {
                    Campaign.findOne({id:$scope.id},function(res)
                    {
                        if(res.data.message.status == 'success')
                        {
                            $scope.campaign = res.data.message.clientsDetails;

                            $scope.campaign.total_clicks = parseInt($scope.campaign.total_clicks);
                            $scope.campaign.clients = {id: parseInt($scope.campaign.client_id)};
                            $scope.campaign.channels= { cd_id:parseInt($scope.campaign.catalogue_detail_cd_id)};
                            $scope.campaign.instructions = $scope.campaign.instruction;
                            $scope.campaign.priority = {value: $scope.campaign.priority};
                            $scope.campaign.total_impressions = parseInt($scope.campaign.total_impression);
                            $scope.campaign.scenario = "update";
                            
                            Campaign.getDeliveryChannel($scope.campaign.clients,function(res){

                                    $scope.storesList =  res.data.message.clientsDetails.stores;

                                    $scope.campaign.clients = {id: parseInt($scope.campaign.client_id)};
                                    $scope.campaign.channels= { cd_id:parseInt($scope.campaign.catalogue_detail_cd_id)};
                                    
                                    Campaign.getPreferedStores($scope.campaign,function(res){

                                            $scope.preferedStoresList =  res.data.message.clientsDetails.items;
                                            
                                            $scope.campaign.preferred_store = $scope.campaign.stores;

                                    });
                              });
                        }
                        else
                        {
                            $scope.campaign = {scenario:'insert'};
                            $scope.campaign.scenario = "insert";
                            $scope.campaign.priority = {value:3};

                        }
                    });
                    
                }
                else
                {
                    $scope.campaign = {scenario:'insert'};
                $scope.campaign.priority = {value:3};
       

                }
               
                $scope.grid.title = "Campaigns";
                $scope.submitted = false;
 
                $scope.clientList = null;
                
                Client.findAll({},function(res){
                   $scope.clientList = res.data.message.clientsDetails.items;
                });

                $scope.saveCampaign = function (form)
                {
                    form.submitted = true;

                    if (form.$valid)
                    {
                        Campaign.save($scope.campaign,function(res){
                            
                            if(res.data.message.status == 'success')
                            {
                                $scope.campaign = {};
                                form.$setPristine();
                                form.submitted = false;
                                alertService.add(res.data.message.status, res.data.message.msg);
                                $location.url('campaigns-list?alert=true');
                            }
                            else
                            {
                                alertService.add(res.data.message.status, res.data.message.msg);
                             
                            }
                        });
                 
                    }
                    

                };

                
                
                $scope.clientChange = function ()
                {
                    if(typeof $scope.campaign.clients != 'undefined' && $scope.campaign.clients != null )
                    {
                        Campaign.getDeliveryChannel($scope.campaign.clients,function(res){

                            $scope.storesList =  res.data.message.clientsDetails.stores;

                        });
                    }
                    else
                    {
                        $scope.storesList = null; 
                    }
                };
                $scope.preferedStoresList =[];
                
                $scope.changeDeliveryChannel = function ()
                {

                    if(typeof $scope.campaign.channels != 'undefined' && $scope.campaign.channels != null )
                    {

                        Campaign.getPreferedStores($scope.campaign,function(res){

                            $scope.preferedStoresList =  res.data.message.clientsDetails.items;
                            $scope.campaign.preferred_store = $scope.preferedStoresList; 
                      });
                    }
                    else
                    {
                        $scope.preferedStoresList = null; 
                        $scope.campaign.preferred_store = null;
                    }
                    
                    
                };
                
                $scope.goToCampaign = function()
                {
                    $location.url("campaigns-list");
                };
                
                $scope.reset = function(form)
                {
                    form.$setPristine();
                    form.submitted = false;
                    $scope.campaign.scenario = 'insert';
                    alertService.reset();
                    $location.url('campaigns');
                    $route.reload();
                };
                
            }
        };
    }]);