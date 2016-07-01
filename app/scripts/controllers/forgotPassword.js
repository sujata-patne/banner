appcontrollers.controller('forgotPasswordController', [ '$location', '$appStorage', '$scope', '$routeParams','User','alertService','UserService','$timeout','$state', function( $location, $storage, $scope, $params,User,alertService,UserService,$timeout,$state) {
            
            $scope.user = {};
            
            $scope.loggedUser =  UserService.getCurrentUser();                    
             


             if( $scope.loggedUser != null)
             {
                $location.url('dashboard');
             }
            
            
            $scope.forgotPassword = function (frm) {
            
                frm.submitted = true;
                if(frm.$valid)
                {
                    User.forgotPassword($scope.user,function(res){
                        
                        if(res.data.message.status =='success')
                        {
                            $scope.reset(frm);
                        }
                        alertService.add(res.data.message.status,res.data.message.msg);
                        
                    })  ; 
                }
            };
            
            $scope.loggedUser = null;
            
            $scope.changePassword = function (frm) {   
                
                frm.submitted = true;                

                if(frm.$valid)
                {
                    $scope.loggedUser =  UserService.getCurrentUser();                    
                    $scope.user.access_token = $scope.loggedUser.access_token;
                    User.changePassword($scope.user,function(res){
                        
                       if(res.data.message.status == 'success')
                       {
                            frm.$setPristine();
                            frm.submitted = false;
                            $scope.user = {};
                            alertService.add(res.data.message.status,res.data.message.msg);
                            $timeout(function () { 
                                $scope.user = UserService.logtoutCurrentUser();
                                $location.url('login');

                            }, 3000);   
                        }
                         else
                         {
                         
                            alertService.add(res.data.message.status,res.data.message.msg);

                         }
                        
                        
                    })  ; 
                }
            };
            
            
                        
            $scope.user ={}; 

            $scope.reset = function (form)
            {
                form.$setPristine();
                form.submitted = false;
                $scope.user = {};
                alertService.reset();
            };
            
           $scope.changePassowrdToText = function(id) 
            {

                document.getElementById(id).type ='text';
              
            };
            
            $scope.changeTextToPassowrd = function(id) 
            {
                document.getElementById(id).type ='password';

            };


         
  }]);
  
appcontrollers.controller('changePasswordController', [ '$location', '$appStorage', '$scope', '$routeParams','User','alertService','UserService','$timeout','$state', function( $location, $storage, $scope, $params,User,alertService,UserService,$timeout,$state) {
            
            $scope.user = {};
            
            $scope.loggedUser =  UserService.getCurrentUser();

             if( $scope.loggedUser == null )
             {
                $location.url('dashboard');
             }
            
            
            $scope.forgotPassword = function (frm) {

                frm.submitted = true;
                if(frm.$valid)
                {
                    User.forgotPassword($scope.user,function(res){

                        if(res.data.message.status =='success')
                        {
                            $scope.reset(frm);
                        }
                        alertService.add(res.data.message.status,res.data.message.msg);
                        
                    })  ; 
                }
            };
            
            $scope.loggedUser = null;
            
            $scope.changePassword = function (frm) {

                frm.submitted = true;                

                if(frm.$valid)
                {
                    $scope.loggedUser =  UserService.getCurrentUser();                    
                    $scope.user.access_token = $scope.loggedUser.access_token;
                    User.changePassword($scope.user,function(res){
                        
                       if(res.data.message.status == 'success')
                       {
                            frm.$setPristine();
                            frm.submitted = false;
                            $scope.user = {};
                            alertService.add(res.data.message.status,res.data.message.msg);
                            $timeout(function () { 
                                $scope.user = UserService.logtoutCurrentUser();
                                $location.url('login');

                            }, 3000);   
                        }
                         else
                         {
                            alertService.add(res.data.message.status,res.data.message.msg);

                         }
                        
                        
                    })  ; 
                }
            };
            
            
                        
            $scope.user ={}; 

            $scope.reset = function (form)
            {
                form.$setPristine();
                form.submitted = false;
                $scope.user = {};
                alertService.reset();
            };
            
           $scope.changePassowrdToText = function(id) 
            {

                document.getElementById(id).type ='text';
              
            };
            
            $scope.changeTextToPassowrd = function(id) 
            {
                document.getElementById(id).type ='password';

            };


         
  }]);
