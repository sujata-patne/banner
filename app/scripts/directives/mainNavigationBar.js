directives.directive('mainNavigationBar', ['$appLocation', 'UserService','User', '$location','alertService', function ($appLocation, UserService,User, $location,alertService) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/main_navigation_bar'),
            controller: function ($scope)
            {
                $scope.onlyUserTypes = function (types)
                {
                    $scope.user = UserService.getCurrentUser();
                    var flag = false;

                    if (typeof $scope.user != 'undefined' && $scope.user != null && $scope.user.hasOwnProperty('ld_role'))
                    {

                        angular.forEach(types, function (type) {
                            if ($scope.user.ld_role === type)
                            {
                                flag = true;
                            }
                     
                        });

                        return flag;

                    }

                }
                $scope.checkLoggedInUser = function ()
                {
                    $scope.user = UserService.getCurrentUser();

                    if ($scope.user != null)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

                $scope.$on('authorized', function (event, data) {

                    $scope.user = data.user.ld_display_name;

                });

                $scope.logout = function ()
                {
                    $scope.user = UserService.logtoutCurrentUser();
                    User.logoutUser($scope.user, function(res) {  

                        if (res.data.message.status == 'success') {
                            $location.url('login')

                        }
                        else {
                            alertService.add(res.data.message.status, res.data.message.msg);
                            $location.url('dashboard');
                        }
                    } );
                    
                };
            }
        };
    }])