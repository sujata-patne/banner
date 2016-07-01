
services.factory('Banner', ['$appStorage', '$appSanitize', '$q', '$http', function ($storage, $sanitize, $q, $http) {

        return {
            getDeliveryChannel: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getDeliveryChannel'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getPreferedStores: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getPreferedStores'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            findOne: function (data, success)
            {
                data = $.param(data);


                return $http({
                    url: CONFIG.getApiUrl('Banner/findOne'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            removeById: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/removeBannerImage'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            find: function (data, success)
            {
                data = $.param(data);
				console.log("CONFIG.getApiUrl-");
                console.log(CONFIG.getApiUrl('Banner/getBanners'));
                return $http({
                    url: CONFIG.getApiUrl('Banner/getBanners'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getPublishBanners: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getPublishBanners'),
                    method: "POST",
                    data: data,
                }).then(function (res) {

                    success(res);
                });
            },
            getPagesBySite: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getPagesBySite'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getPlacesByPage: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getPlacesByPage'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getOperatorsList: function (success)
            {

                return $http({
                    url: CONFIG.getApiUrl('Banner/getOperatorsList'),
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getTimeslotList: function (success)
            {

                return $http({
                    url: CONFIG.getApiUrl('Banner/getTimeslotList'),
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getCircleList: function (success)
            {

                return $http({
                    url: CONFIG.getApiUrl('Banner/getCircleList'),
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getHandsetList: function (data,success)
            {
                data = $.param(data); 
                return $http({
                    url: CONFIG.getApiUrl('Banner/getHandsetList'),
                    method: "POST",
                    data:data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getHandsetFilter: function (data,success)
            {
                data = $.param(data); 
                return $http({
                    url: CONFIG.getApiUrl('Banner/getHandsetFilter'),
                    method: "POST",
                    data:data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getOperatingSystemList: function (success)
            {

                return $http({
                    url: CONFIG.getApiUrl('Banner/getOperatingSystemList'),
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            getHandsetGroup: function (success)
            {

                return $http({
                    url: CONFIG.getApiUrl('Banner/getHandsetGroup'),
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            findAll: function (data, success)
            {
                data = $.param(data);


                return $http({
                    url: CONFIG.getApiUrl('Client/getClients'),
                    method: "POST",
  //                  data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            findAllByClient: function (data, success)
            {
                data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('Banner/getCampaignsByClient'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            save: function (banner, success)
            {

                var data = $.param(banner);

                return $http({
                    url: CONFIG.getApiUrl('Banner/create'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });



            },
            removeManager: function (id, success)
            {
                var data = $.param({ld_id: id});
                return $http({
                    url: CONFIG.getApiUrl('campaign/block'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);

                });


            },
            activateManager: function (id, success)
            {
                var data = $.param({ld_id: id});
                return $http({
                    url: CONFIG.getApiUrl('campaign/activate'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);

                });


            },
            downloadBannerList: function (data, success)
            {
                var data = $.param(data);
                return $http({
                    url: CONFIG.getApiUrl('banner/downloadBannerList'),
                    method: "POST",
                    data: data,
                }).then(function (res) {

                    success(res);

                });


            },
            removePublishedBanner: function(data,success)
            {
                var data = $.param(data); 
                
                return $http({
                   url: CONFIG.getApiUrl('banner/bannerRemove'),
                    method: "POST",
                    data: data
                }).then(function(res){
                    
                    success(res);
                });
                
            }
        };
    }]);