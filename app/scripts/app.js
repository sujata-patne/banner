var App = window.App = angular.module('App',
        [
            'ngRoute',
            'Scope.safeApply',
            'App.Controllers',
            'App.Filters',
            'App.Services',
            'App.Directives',
            'App.Routes',
       //     'ui.unique',
            'ui.bootstrap',
            'ui.router',
            'angularjs-datetime-picker',
            'angular-storage',
            'ngProgress',
            'fileUpload',
        //    'ngFileUpload'
        ]
        );


/*
 *  This factory is used to authorization of user and authentication of logging user 
 */

App.factory('authorization', ['$http', 'UserService', '$rootScope', '$location',
    function ($http, UserService, $rootScope, $location) {

        return {
            getConditions: function (access, userLoggedIn)
            {
                if (userLoggedIn != null)
                {
                    access.access_token = userLoggedIn.access_token;
                }
                else
                {
                    access.access_token = null;
                    $location.url('login')

                }

                var data = $.param(access);

                $http({
                    url: CONFIG.getApiUrl('User/loginCheck'),
                    method: "POST",
                    data: data,                  
                }).then(function (res) {

                    if (res.data.message.status_code == 401) {
                        UserService.logtoutCurrentUser();
                        $rootScope.$broadcast('unauthorized');
                        $location.url('login')
                    }

                    if (res.data.message.status_code == 403) {

                        UserService.logtoutCurrentUser();
                        $rootScope.$broadcast('unauthorized');
                        $location.url('login');
                    }

                    if (res.data.message.status_code == 200) {
                        $rootScope.$broadcast('authorized', res.data.message);
                    }



                });

            }
        };
    }]);


App.service('UserService', function (store,$location) {
    var service = this,
            currentUser = null;

    service.setCurrentUser = function (user) {
        currentUser = user;
        store.set('user', user);
        return currentUser;
    };

    service.getCurrentUser = function () {
        if (!currentUser) {

            currentUser = store.get('user');

        }
        return currentUser;

    };
    service.logtoutCurrentUser = function () {
        currentUser = null;
        store.remove('user');

        return currentUser;
    };
    
    service.redirect = function()
    {
        
        var user = this.getCurrentUser();


       if(user == null ||  typeof user.ld_role == 'undefined'  )
       { 
           $location.url('login');

       }
        else   if( user.ld_role == 'admin'  )
       { 
           $location.url('account-managers');

       } else   if( user.ld_role == 'AccountManager')
       {
            $location.url('campaigns');
       }
       else
       {
           $location.url('login');
       }
   };
}).service('AkAlert', function (store) {
    this.alertData = null;
    this.show = function (msgObj)
    {

        store.set('alertData', msgObj);

    };

    this.checkAlertData = function ()
    {

        this.alertData = store.get('alertData');

        if (this.alertData != null)
        {
            return true;
        }
        else
        {
            return false;
        }

    };


});



App.factory('alertService',
        function ($rootScope) {

            var alertService = {};

            // create an array of alerts available globally
            $rootScope.alerts = [];


            alertService.reset = function ()
            {
                $rootScope.alerts = [];

            };

            alertService.add = function (type, msg) {
                alertService.reset();
                $rootScope.alerts.push({'type': type, 'msg': msg});
            };

            alertService.closeAlert = function (index) {
                $rootScope.alerts.splice(index, 1);
            };

            return alertService;

        });
        
App.filter("unique",function(){return function(e,t){if(t===!1)return e;if((t||angular.isUndefined(t))&&angular.isArray(e)){var n={},r=[],i=function(e){return angular.isObject(e)&&angular.isString(t)?e[t]:e};angular.forEach(e,function(e){var t,n=!1;for(var s=0;s<r.length;s++)if(angular.equals(i(r[s]),i(e))){n=!0;break}n||r.push(e)}),e=r}return e}});