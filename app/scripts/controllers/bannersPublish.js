appcontrollers.controller('bannerPublishController', [ '$location', '$appStorage', '$scope', '$routeParams','Banner','Campaign','$rootScope', function( $location, $storage, $scope, $params,Banner,Campaign,$rootScope) {


    $scope.banner = {}; 
    $scope.id = null; 
    
    if( typeof $params.id != 'undefined')
    {
        $scope.id = $params.id;  
    }
    
    if( typeof $params.edit != 'undefined')
    {
        $scope.edit = $params.edit;  
        $scope.published_id = $params.published_id;  
        
    }
    else
    {
        $scope.edit = false;  
        
    }
    
    
    
         
  }]);
