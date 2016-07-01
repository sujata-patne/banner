angular.module('App.Routes', [])

  .config(['$routeProvider', '$locationProvider','$httpProvider','$locationProvider', function($routeProvider, $locationProvider,$httpProvider,$locationProvider) {


 $httpProvider.interceptors.push('httpRequestInterceptor');

//// alternatively, register the interceptor via an anonymous factory
//$httpProvider.interceptors.push(function($q,UserService) {
//  return {
//   'request': function(config) {
//       // same as above
//       
//       return config;
//    },
//
//    'response': function(response) {
//       // same as above
//       
//       return response;
//    }
//  };
//});
                  
//     $httpProvider.defaults.withCredentials = true;


    if(CONFIG.routing.html5Mode) {
      $locationProvider.html5Mode(true);
    }
    else {
      var routingPrefix = CONFIG.routing.prefix;
      if(routingPrefix && routingPrefix.length > 0) {
        $locationProvider.hashPrefix(routingPrefix);
      }
    }

    ROUTER.when('dashboard_path', '/dashboard', {
      controller : 'DashboardCtrl',
      templateUrl : CONFIG.prepareViewTemplateUrl('videos/index'),
       access: {
            requiresLogin: true,
            requiredPermissions: ['admin', 'AccountManager'],
            permissionType: 'any'
        }
    });

    ROUTER.when('account_managers_management_path', '/account-managers', {
      controller : 'accountManagersController',
      templateUrl : CONFIG.prepareViewTemplateUrl('accountmanagers/index'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin'],
            permissionType: 'AtLeastOne'
        }

    });


    ROUTER.when('external_clients_path', '/external-clients', {
      controller : 'externalClientsController',
      templateUrl : CONFIG.prepareViewTemplateUrl('clients/index'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin'],
            permissionType: 'AtLeastOne'
        }
    });

    ROUTER.when('external_clients_list_path', '/external-clients-list', {
      controller : 'externalClientsController',
      templateUrl : CONFIG.prepareViewTemplateUrl('clients/list'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin'],
            permissionType: 'AtLeastOne'
        }
    });

    ROUTER.when('external_clients_update_path', '/external-clients-update/:id', {
      controller : 'externalClientsController',
      templateUrl : CONFIG.prepareViewTemplateUrl('clients/update'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin'],
            permissionType: 'AtLeastOne'
        }
    });
    
          
    ROUTER.when('campaign_update_path', '/campaign-update/:id', {
      controller : 'campaignController',
      templateUrl : CONFIG.prepareViewTemplateUrl('campaign/update'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    

    ROUTER.when('other_path', '/other', {
      controller : 'OtherCtrl',
      templateUrl : CONFIG.prepareViewTemplateUrl('other/index'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin', 'AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
    ROUTER.when('campaign_path', '/campaigns', {
      controller : 'campaignController',
      templateUrl : CONFIG.prepareViewTemplateUrl('campaign/index'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
    ROUTER.when('campaign_list_path', '/campaigns-list', {
      controller : 'campaignController',
      templateUrl : CONFIG.prepareViewTemplateUrl('campaign/list'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin', 'AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
    ROUTER.when('banners_path', '/banners', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/index'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
    ROUTER.when('banner_list_path', '/banner-list', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/list'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin','AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });

    ROUTER.when('add_group_path', '/add-group', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/add_group'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin','AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });

    ROUTER.when('banner_publish_path', '/banner-publish', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/publish'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['admin','AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });


    ROUTER.when('banner_publish_form_path', '/banner-publish/:id', {
      controller : 'bannerPublishController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/publish_form'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });

    ROUTER.when('banner_publish_edit_path', '/banner-publish-edit/:id', {
      controller : 'bannerPublishController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/publish_form'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });

          
    ROUTER.when('banner_update_path', '/banner-update/:id', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/update'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
    ROUTER.when('banner_view_path', '/banner-view/:id', {
      controller : 'bannerController',
      templateUrl : CONFIG.prepareViewTemplateUrl('banner/view'),
        access: {
            requiresLogin: true,
            requiredPermissions: ['AccountManager'],
            permissionType: 'AtLeastOne'
        }
    });
    
          
          
    ROUTER.when('login_path', '/login', {
      controller : 'loginController',
      templateUrl : CONFIG.prepareViewTemplateUrl('site/login')
    });

    ROUTER.when('forgot_password_path', '/forgot-password', {
      controller : 'forgotPasswordController',
      templateUrl : CONFIG.prepareViewTemplateUrl('site/forgot_password'),

    });

    ROUTER.when('password_change_path', '/password-change', {
      controller : 'changePasswordController',
      templateUrl : CONFIG.prepareViewTemplateUrl('site/change_password'),
      
        access: {
               requiresLogin: true,
               requiredPermissions: ['AccountManager','admin'],
               permissionType: 'AtLeastOne'
           }
    });

    ROUTER.alias('home_path', 'dashboard_path');

    ROUTER.otherwise({
      redirectTo : '/dashboard'
    });

    ROUTER.install($routeProvider);
    
//    $locationProvider.html5Mode(true);

    
  }]).

  run(['$rootScope', '$location','$filter', function($rootScope, $location,$filter) {
          
    var prefix = '';
    if(!CONFIG.routing.html5Mode) {
      prefix = '#' + CONFIG.routing.prefix;
    }
    $rootScope.route = function(url, args) {
        
      return prefix + ROUTER.routePath(url, args);
    };

    
    $rootScope.$on('userLoggingIn',function(data){
       
       // console.log(data);
        
    });

    $rootScope.r = $rootScope.route;

    $rootScope.c = function(route, value) {
      var url = ROUTER.routePath(route);
      if(url == $location.path()) {
        return value;
      }
    };
    
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };
    
    
    $rootScope.convertToDate = function(date)
    {
       // console.log(date.split('-').join('/'));
        if(typeof date != 'undefined')
        {
            date = date.split('-'); 
            date = date[1] +"/"+date[0]+"/" +date[2];
            date= date.replaceAll('-','/',2);
            return  $filter('date')(new Date(date),'dd-MM-yyyy HH:mm:ss');
        }
    };
    
    $rootScope.convertToTimeStamp = function(date)
    {
       // console.log(date.split('-').join('/'));
        if(typeof date != 'undefined')
        {
            date = date.split('-'); 
            date = date[1] +"/"+date[0]+"/" +date[2];
            date= date.replaceAll('-','/',2);
            return  Date.parse(date);
        }
    };
    
    $rootScope.convertDbDate = function(date)
    {
        if(typeof date != 'undefined')
        {
            date= date.replaceAll('-','/',2); 
            return  $filter('date')(new Date(date),'dd-MM-yyyy HH:mm:ss');
        }
        
    };
    
    $rootScope.getBannerImage = function(img)
    {
        if(typeof img != 'undefined')
        {
            return CONFIG.getUploadUrl(img);    
        }
        else
        {
            return CONFIG.getUploadUrl('noimages.jpg');
        }
    };
    
    $rootScope.getDateFormatString = function(){
        
        return "dd-MM-yyyy HH:mm:ss";
    };
    
    $rootScope.getPriorityName = function(index){
        
        var priorities = [
                    {name:"Very Low",value:1},
                    {name:"Low",value:2},
                    {name:"Medium",value:3},
                    {name:"High",value:4},
                    {name:"Very High",value:5}
                ];

        
        
        if(typeof priorities[index] != 'undefined')
        {
            return priorities[index].name;
        }
        else
        {
            return priorities[2].name;
        }
    };
    
          
  }]);
