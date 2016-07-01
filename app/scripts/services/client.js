
services.factory('Client', ['$appStorage', '$appSanitize', '$q', '$http', function ($storage, $sanitize, $q, $http) {

        return {
            getDeliveryChannel: function (data, success)
            {
                data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('Client/getDeliveryChannel'),
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
                    url: CONFIG.getApiUrl('Client/findOne'),
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
                    url: CONFIG.getApiUrl('Client/getClients'),
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
            save: function (client, success)
            {

                var data = $.param(client);

                $http({
                    url: CONFIG.getApiUrl('Client/create'),
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
                    url: CONFIG.getApiUrl('client/block'),
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
                    url: CONFIG.getApiUrl('client/activate'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);

                });


            },
        };
    }])