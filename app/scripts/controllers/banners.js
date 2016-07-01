appcontrollers.controller('bannerController', [ '$location', '$appStorage', '$scope', '$routeParams','Banner','Campaign','$rootScope','Banner', function( $location, $storage, $scope, $params,Banner,Campaign,$rootScope,Banner) {
        
    $scope.banner = {}; 
    $scope.id = null; 
    
    if( typeof $params.id != 'undefined')
    {
        $scope.id = $params.id;  

        Banner.findOne({id: $scope.id}, function (res)
        {
            if (res.data.message.status == 'success')
            {
                $scope.banner = res.data.message.clientsDetails;

                $scope.banner.name = $scope.banner.banner_name;
                $scope.banner.clients = {id: $scope.banner.client_id};

                Campaign.getDeliveryChannel($scope.banner.clients, function (res) {

                    $scope.storesList = res.data.message.clientsDetails.stores;

                    $scope.banner.channels = {cd_id: $scope.banner.catalogue_detail_cd_id};

                    Campaign.findAllByClient($scope.banner, function (res) {
                        $scope.banner.scenario = "update";
                    });
                });
            }
        });
          
    }
    
    
    $scope.goToBanners = function ()
    {
        $location.url("banner-list");
    };




    $scope.goNext = function (banner)
    {
        $location.url('banner-list/?alert=true');
    };
    
    $scope.goBack = function (banner)
    {
            $location.url('banner-update/'+banner.id+'?alert=true');
    };
    

    $scope.downloadBannerList = function()
    {
        
        
            Banner.downloadBannerList({},function(res)
            {
                
                if(res.data.message.status === 'success')
                {
                    window.location = "/download.php";
                }
                else
                {
                    console.log('could not create file ');
                }
            });
        
        
        
    };

         
         
  }]);
