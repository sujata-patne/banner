directives.directive('bannerListGrid', ['$appLocation', 'Store', 'Client', 'UserService', 'User', '$location','$uibModal','alertService','Campaign','Banner', function ($appLocation, Store, Client, UserService, User, $location,$uibModal,alertService,Campaign,Banner) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/banner_list_grid'),
            controller: function ($scope)
            {
                $scope.itemsData = [];

                $scope.orderByField = 'id'; //Added orderByField and reverseSort paramenter in input object for sorting and pagination banners list -Shivaji G.
                $scope.reverseSort = true;

                $scope.listCriteria = {
                    perPageItems: 10,
                    currentPage: 0,
                    totalItemsFound: 0,
                    userType: 'AccountManager',
                    orderBy:"id desc"
                };

                $scope.publishBanner = function (id)
                {
                    $location.url("banner-publish/" + id);
                };

                //Added orderBy function for sorting and pagination published banners list -Shivaji G.
                $scope.OrderBy=function (orderByField,reverseSort) {
           
                    var sortOrder =reverseSort==false?"asc":"desc";

                    $scope.listCriteria = {
                        perPageItems: 10,
                        currentPage: $scope.listCriteria.currentPage,
                        totalItemsFound: 0,
                        userType: 'AccountManager',
                        orderBy:orderByField +" "+sortOrder
                    };
                    $scope.getBanners();
                };

                $scope.getBanners = function ()
                {                    
                    Banner.find($scope.listCriteria, function (res) {
                        if (res.data.message.status == 'success')
                        {
                            $scope.itemsData = res.data.message.clientsDetails;
                     
                        }
                    });

                };

                $scope.getBanners();


                $scope.updateBanner = function (id)
                {
                    $location.url("banner-update/" + id);
                };


                $scope.pageChanged = function ()
                {
                    $scope.getBanners();
                };

                // TO DEACTIVATE CLIENT

                
                $scope.removeManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to block client no " + ld_id + "? ");
                    if (cnfm)
                    {
                        Campaign.removeManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getBanners();


                        });
                    }
                };

                $scope.activateManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to activate client no " + ld_id + "? ");
                    if (cnfm)
                    {
                        Campaign.activateManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getBanners();


                        });
                    }
                };


                // FOR MODAL OF STORE LIST 
                $scope.animationsEnabled = true;

                $scope.showSlideShow = function (stores) {

                    var modalInstance = $uibModal.open({
                        animation: $scope.animationsEnabled,
                        templateUrl: 'imageSlideShow.html',
                        controller: function ($scope, $uibModalInstance) {


                            $scope.images = stores;

                            $scope.ok = function () {
                                $uibModalInstance.close();
                            };

                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'small',
                        resolve: {
                            items: function () {
                                return $scope.iamges;
                            }
                        }
                    });

                    modalInstance.result.then(function (selectedItem) {

                    }, function () {
                        //    $log.info('Modal dismissed at: ' + new Date());
                    });
                };

            }
        };
    }]);
