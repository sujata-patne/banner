
services.factory('BannerPublish', ['$appStorage', '$appSanitize', '$q', '$http', function ($storage, $sanitize, $q, $http) {

        return {
            save: function (banner, success)
            {
                var data = $.param(banner);
                $http({
                    url: CONFIG.getApiUrl('Banner/BannerPublish'),
                    method: "POST",
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (res) {

                    success(res);
                });



            }

        };
    }]);