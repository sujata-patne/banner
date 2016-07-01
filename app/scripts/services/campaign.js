
services.factory('Campaign', ['$appStorage', '$appSanitize', '$q', '$http', function ($storage, $sanitize, $q, $http) {

        return {
            getDeliveryChannel: function (data, success)
            {
                data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('Campaign/getDeliveryChannel'),
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
                $http({
                    url: CONFIG.getApiUrl('Campaign/getPreferedStores'),
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


                $http({
                    url: CONFIG.getApiUrl('Campaign/findOne'),
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


                $http({
                    url: CONFIG.getApiUrl('campaign/getCampaings'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            findAll: function (data, success)
            {
                data = $.param(data);


                $http({
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
                $http({
                    url: CONFIG.getApiUrl('Campaign/getCampaignsByClient'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });
            },
            save: function (campaign, success)
            {

                var data = $.param(campaign);

                $http({
                    url: CONFIG.getApiUrl('Campaign/create'),
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
                $http({
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
                $http({
                    url: CONFIG.getApiUrl('campaign/activate'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);

                });


            }
        };
    }]);