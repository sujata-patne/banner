var directives = angular.module('App.Directives', [])

        .directive('appWelcome', function () {
            return function ($scope, element, attrs) {
                var html = element.html();
                element.html('Welcome: <strong>' + html + '</strong>');
            };
        })

        //  This will be  responsible for the main navigation bar of the site wehre  all menus contens


        .directive('numericOnly', function () {
            return {
                require: 'ngModel',
                link: function (scope, element, attrs, modelCtrl) {

                    modelCtrl.$parsers.push(function (inputValue) {
                        var transformedInput = inputValue ? inputValue.replace(/[^\d.-]/g, '') : null;

                        if (transformedInput != inputValue) {
                            modelCtrl.$setViewValue(transformedInput);
                            modelCtrl.$render();
                        }

                        return transformedInput;
                    });
                }
            };
        }).directive('dateTimePicker', function ($timeout, $parse) {
    return {
        link: function ($scope, element, $attrs) {
            return $timeout(function () {
                var ngModelGetter = $parse($attrs['ngModel']);

                return $(element).datetimepicker(
                        {
                            minDate: moment().add(1, 'd').toDate(),
                            sideBySide: true,
                            allowInputToggle: true,
                            locale: "tr",
                            useCurrent: false,
                            defaultDate: moment().add(1, 'd').add(1, 'h'),
                            icons: {
                                time: 'icon-back-in-time',
                                date: 'icon-calendar-outlilne',
                                up: 'icon-up-open-big',
                                down: 'icon-down-open-big',
                                previous: 'icon-left-open-big',
                                next: 'icon-right-open-big',
                                today: 'icon-bullseye',
                                clear: 'icon-cancel'
                            }
                        }
                ).on('dp.change', function (event) {
                    $scope.$apply(function () {
                        return ngModelGetter.assign($scope, event.target.value);
                    });
                });
            });
        }
    };
})



        .directive('uniqueEmail', function ($http) {
            var toId;
            return {
                restrict: 'A',
                require: 'ngModel',
                link: function (scope, elem, attr, ctrl) {
                    
                    elem.bind('blur', function(e) {
                        
                            var value =this.value; 

                            // if there was a previous attempt, stop it.
                            if (toId)
                                clearTimeout(toId);
                            // start a new attempt with a delay to keep it from
                            // getting too "chatty".
                            toId = setTimeout(function () {
                                // call to some API that returns { isValid: true } or { isValid: false }
                                var data = $.param({email: value});
                                
                                if (typeof value != 'undefined')
                                {
                                    if (scope.user.scenario != 'update')
                                    {
                                        $http({
                                            url: CONFIG.getApiUrl('User/EmailValid'),
                                            method: "POST",
                                            data: data,
                                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                                        }).then(function (res) {
                                            //set the validity of the field
                                            ctrl.$setValidity('uniqueEmail', res.data.message.isValid);

                                        });
                                    }
                                }


                            }, 200);

                    });
                }
            };
        }).directive('existingEmail', function ($http) {
    var toId;
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            //when the scope changes, check the email.
            scope.$watch(attr.ngModel, function (value) {
                // if there was a previous attempt, stop it.
                if (toId)
                    clearTimeout(toId);
                // start a new attempt with a delay to keep it from
                // getting too "chatty".
                toId = setTimeout(function () {
                    // call to some API that returns { isValid: true } or { isValid: false }
                    var data = $.param({email: value});

                    if (typeof value != 'undefined')
                    {
                        $http({
                            url: CONFIG.getApiUrl('User/EmailValid'),
                            method: "POST",
                            data: data,
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        }).then(function (res) {
                            //set the validity of the field
                            ctrl.$setValidity('existingEmail', !res.data.message.isValid);

                        });
                    }

                }, 200);


            });
        }
    };
}).directive('alert', function (alertService, $rootScope) { //injects the Alert serivce
    return {
        templateUrl: CONFIG.prepareViewTemplateUrl('directives/alert'),
        restrict: 'EA',
        controller: function ($scope)
        {
            $scope.alertService = alertService;
        }

    };
})
        .directive('loading', ['$http', 'ngProgressFactory', '$rootScope', function ($http, ngProgressFactory, $rootScope)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
                        scope.progress = ngProgressFactory.createInstance();

                        scope.progress.start();
                         scope.isLoading = function () {
                            return $http.pendingRequests.length > 0;
                        };

                        scope.$watch(scope.isLoading, function (v)
                        {
                            if (v) {

                                $rootScope.loading = true;
                                
                                
                                scope.progress.start();
                                
                                elm.show();
                            } else {
                                elm.hide();
                                $rootScope.loading = false;
                                scope.progress.complete();
                            }
                        });
                    }
                };

            }])
            .directive('uniqueNess', function ($http) {
            var toId;
            return {
                restrict: 'A',
                require: 'ngModel',
               
                link: function (scope, elem, attr, ctrl) {


                    elem.bind('blur', function(e) {
                        
                            var value =this.value; 


                        // if there was a previous attempt, stop it.
                        if (toId)
                            clearTimeout(toId);
                        // start a new attempt with a delay to keep it from
                        // getting too "chatty".
                        toId = setTimeout(function () {
                            
                            var objectName = {};

                            
                            // call to some API that returns { isValid: true } or { isValid: false }
                            var data = $.param({value: value,
                                column:attr.columnname,
                                table: attr.tablename,
                                scenario:attr.scenario,
                                objectId:attr.objectId
                                
                            });
                            if (typeof value != 'undefined' || typeof attr.scenario != 'undefined' || attr.scenario !='' )
                            {

                                    $http({
                                        url: CONFIG.getApiUrl('User/checkUnique'),
                                        method: "POST",
                                        data: data,
                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                                    }).then(function (res) {
                                        //set the validity of the field
                                       
                                        ctrl.$setValidity('uniqueNess', res.data.message.isValid);

                                    });
                                
                            }


                        }, 200);

                    });
                }
            };
        })


        .directive('futureDateOnly', function ($http,$rootScope,$filter) {
            var toId;
            return {
                restrict: 'A',
                require: 'ngModel',               
                link: function (scope, elem, attr, ctrl) {
                   var d1,d2;
                    //when the scope changes, check the email.
                    scope.$watch(attr.ngModel, function (value) {
                        
                        console.log(attr.scenario); 
                        
                        if(attr.scenario =='insert')
                        {
                            if(typeof value != 'undefined')
                             {
                                 d2 =   $rootScope.convertToTimeStamp(value);
                                 d1 =   new Date();
                                 d1 = Date.parse(d1); 

                                 if(d1 > d2)
                                 {
                                     ctrl.$setValidity('futureonly', false);
                                 }
                                 else
                                 {
                                     ctrl.$setValidity('futureonly', true);
                                 }
                             }
                         }
                         
                         
                    });
                }
            };
        })
        
        
        .directive('chekMinDate', function ($http,$rootScope,$filter) {
            var toId;
            return {
                restrict: 'A',
                require: 'ngModel',
               
                link: function (scope, elem, attr, ctrl) {
                    
                   var d1,d2;
                    scope.$watch(attr.compare, function (value) {
                       
                        if(typeof value != 'undefined')
                        {
                            d1 =   $rootScope.convertToTimeStamp(value);

                             if(d1 > d2)
                             {
                                 ctrl.$setValidity('minDate', false);
                             }
                             else
                             {
                                 ctrl.$setValidity('minDate', true);
                             }
                        }
                    
                    });


                    //when the scope changes, check the email.
                    scope.$watch(attr.ngModel, function (value) {
                       if(typeof value != 'undefined')
                        {
                        
                            d2 =   $rootScope.convertToTimeStamp(value);
                            if(d1 > d2)
                            {
                                ctrl.$setValidity('minDate', false);
                            }
                            else
                            {
                                ctrl.$setValidity('minDate', true);
                            }
                        }
                    });
                }
            };
        })


        .directive('confirmPassword', function ($http) {
            var toId;
            return {
                restrict: 'A',
                require: 'ngModel',
               
                link: function (scope, elem, attr, ctrl) {

                    scope.new_passowrd = null; 
                    scope.conf_passowrd = null; 
                
                    //when the scope changes, check the email.
                    scope.$watch(attr.ngModel, function (value) {
                    
                        scope.conf_passowrd = value;  
                        if(scope.conf_passowrd != scope.new_passowrd)
                        {
                            ctrl.$setValidity('confirmPassword', false);
                        }
                        else
                        {
                            ctrl.$setValidity('confirmPassword', true);
                            
                        }
                    
                    });
                    //when the scope changes, check the email.
                    scope.$watch(attr.newPassword, function (value) {

                        scope.new_passowrd = value;  
                        if(scope.conf_passowrd != scope.new_passowrd)
                        {
                        
                            ctrl.$setValidity('confirmPassword', false);
                        }
                        else
                        {
                            ctrl.$setValidity('confirmPassword', true);
                            
                        }
                    });
                }
            }
        }).directive('imageFilesOnly', function validFile() {

    var validFormats = ['jpg', 'gif','png'];
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, ctrl) {
            
                elem.on('change', function () {
                   var value = elem.val(),
                       ext = value.substring(value.lastIndexOf('.') + 1).toLowerCase();   

                   if(validFormats.indexOf(ext) !== -1) 
                   {
                       ctrl.$setValidity('invalidFile',true); 
                   }
                   else
                   {
                       ctrl.$setValidity('invalidFile',false); 
                       
                    }
                });
          
        }
    };
});
                