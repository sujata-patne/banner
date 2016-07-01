
directives.directive('managerFormGrid', ['$appLocation', '$route', '$rootScope', 'User', 'Store', 'alertService', '$uibModal', function ($appLocation, $route, $rootScope, User, Store, alertService, $uibModal) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/manager_form_grid'),
            controller: function ($scope)
            {
                $scope.managers = [];

                $scope.htmlTooltip = "";

                Store.find({}, function (res) {
                    $scope.storeList = res.message.storeDetails;
                });


                $scope.grid = {};
                $scope.user = {};
                $scope.user.scenario = "insert";

                $scope.grid.title = "Account Managers";
                $scope.submitted = false;

                $scope.removeManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to block user no " + ld_id + "? ");
                    if (cnfm)
                    {
                        console.log("In directives.directive('managerFormGrid'- $scope.removeManager");
                        User.removeManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getUsers();
                            $scope.user = {};

                        });
                    }
                };

                $scope.activateManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to activate user no " + ld_id + "? ");
                    if (cnfm)
                    {
                        User.activateManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getUsers();


                        });
                    }
                };


                $scope.updateManager = function (ld_id)
                {
                    alertService.reset();
                    User.getUserById(ld_id, function (res) {
                        res.data.message.data.ld_mobile_no = parseInt(res.data.message.data.ld_mobile_no);

                        angular.copy(res.data.message.data, $scope.user);
                        $scope.user.scenario = "update";


                    });


                };


                $scope.saveManager = function (form)
                {
                    form.submitted = true;

                    if (form.$valid && $rootScope.loading != true)
                    {
                        User.save($scope.user, function (res) {

                            $scope.user = {};
                            form.$setPristine();
                            form.submitted = false;
                            //                           $route.reload();

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getUsers();

                        });
                    }
                };
                $scope.listCriteria = {
                    perPageItems: 10,
                    currentPage: 0,
                    totalItemsFound: 0,
                    userType: 'AccountManager'
                };

                $scope.reset = function (form)
                {
                    form.$setPristine();
                    form.submitted = false;
                    $scope.user = {};
                    $scope.user.scenario = 'insert';
                    alertService.reset();
                };

                $scope.selectAllStores = function ()
                {
                    if ($scope.storeSelectAll)
                    {
                        $scope.user.stores = $scope.storeList;
                    }
                    else
                    {
                        $scope.user.stores = null;
                    }
                };

                $scope.itemsData = {};
                
                $scope.getUsers = function ()
                {

                    User.findAll($scope.listCriteria, function (res) {
                        $scope.itemsData = res.data.message.data;


                    });

                };

                $scope.getUsers();

                $scope.pageChanged = function ()
                {
                    $scope.getUsers();
                };
                $rootScope.onReady();




                $scope.animationsEnabled = true;

                $scope.open = function (size, stores) {

                    var modalInstance = $uibModal.open({
                        animation: $scope.animationsEnabled,
                        templateUrl: 'myModalContent.html',
                        controller: function ($scope, $uibModalInstance) {


                            $scope.items = stores;
                            $scope.selected = {
                                item: $scope.items[0]
                            };

                            $scope.ok = function () {
                                $uibModalInstance.close($scope.selected.item);
                            };

                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: size,
                        resolve: {
                            items: function () {
                                return $scope.items;
                            }
                        }
                    });

                    modalInstance.result.then(function (selectedItem) {
                        $scope.selected = selectedItem;
                    }, function () {
                        //    $log.info('Modal dismissed at: ' + new Date());
                    });
                };

                $scope.toggleAnimation = function () {
                    $scope.animationsEnabled = !$scope.animationsEnabled;
                };


            }
        };
    }]);