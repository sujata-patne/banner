 appcontrollers.controller('loginController', [ '$location', '$appStorage', '$scope', '$routeParams','User','$rootScope','UserService','alertService', function( $location, $storage, $scope, $params,User,$rootScope,UserService,alertService) {
         
            $scope.user = {};

           UserService.redirect();


            $scope.$on('unauthorized',function(event, data){
               UserService.logtoutCurrentUser();
               
            });
            
            $scope.login = function () {

                $scope.response = {};
              
                 User.loginUser($scope.user,function(res){
                     $scope.response = res.data.message;

                     
                     if($scope.response.status=='success')
                     {
                         $rootScope.$broadcast('userLoggingIn',$scope.response);
                         UserService.setCurrentUser($scope.response.user);

                         if($scope.user.password =='icon')
                         {
                             $location.url('/password-change');
                         }
                         else
                         {
                            $location.url('/dashboard');
                        }
                     }
                     else
                     {  
                        alertService.add(res.data.message.status, res.data.message.msg);
                     }
                     
                 });
            };
                $scope.reset = function (form)
                {
                    alertService.reset();
                    form.$setPristine();
                    form.submitted = false;
                    $scope.user = {};
                };
        
         
  }]);  
