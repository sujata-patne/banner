services.factory('User',['$appStorage','$appSanitize','$q','$http',function($storage, $sanitize, $q, $http) {
   
        return {
            
            forgotPassword:function(data,success)
            {
                var data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('User/forgotPassword'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){
                    
                    success(res);
                     
                 });

 
            },
            
            changePassword:function(data,success)
            {
                var data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('User/changePassword'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){
                    
                    success(res);
                     
                 });

 
            },
            save:function(data,success)
            {
                var data = $.param(data);
                $http({
                    url: CONFIG.getApiUrl('User/saveAccountManager'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){
                        
                        success(res);

                     
                 });

 
            },
            removeManager:function(id,success)
            {
                var data = $.param({ld_id:id});
                $http({
                    url: CONFIG.getApiUrl('User/removeManager'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){
                    
                    success(res);
                     
                 });

 
            },
            activateManager:function(id,success)
            {             
                var data = $.param({ld_id:id});
                $http({
                    url: CONFIG.getApiUrl('User/activateManager'), 
                    method: "POST",
                    data:  data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(res){
                    
                    success(res);
                     
                 });

 
            },
            findAll:function(data,success)
            {
                
                $http({
                    url: CONFIG.getApiUrl('User/findAll'), 
                    method: "POST",
                    params:  data,
                        }).then(function(res){
                        success(res);
                     
                 });

            },
            getUserById:function(id,success)
            {
                   $http({
                    url: CONFIG.getApiUrl('User/findByPk'), 
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
                    params:  {ld_id:id}
                        }).then(function(res){
                        success(res);
                     
                 });
            },
            loginUser:function(user,success)
            {
                var data  = $.param(user);
                
                   $http({
                    url: CONFIG.getApiUrl('User/login'), 
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
                        data: data
                    }).then(function(res){

                        success(res);
                     
                 });
            },
            logoutUser:function(user,success)
            {
                $http({
                    url: CONFIG.getApiUrl('User/logout'),
                    method: "POST"
                //    data: data
                }).then(function(res){

                    success(res);
                    

                });
            }
        };
}]);