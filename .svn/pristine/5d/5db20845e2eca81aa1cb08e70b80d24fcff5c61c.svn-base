
services.factory('Store',['$appStorage','$appSanitize','$q','$http','UserService',function($storage, $sanitize, $q, $http,UserService) {
   
        return {
            find:function(data,success)
            {
                    $http.get(CONFIG.getApiUrl('Store/getStores'),{}).success(function(res){
                        
                        success(res); 
                        
                    }).error(function(err){

                    });
            },
            getStoresByCampaign:function(data,success)
            {
                
                 data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('banner/getStoresByCampaign'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
                    

            },
            findStoreDeliveries:function(data,success)
            {
                data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('Store/getStoresDeliveries'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){                        
                   
                        success(res);
             });

          }

        };
}]);