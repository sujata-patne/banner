directives.directive('clientsListGrid', ['$appLocation', 'Store', 'Client', 'UserService', 'User', '$location','$uibModal','alertService', function ($appLocation, Store, Client, UserService, User, $location,$uibModal,alertService) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/clients_list_grid'),
            controller: function ($scope)
            {


                $scope.itemsData = [];

                $scope.listCriteria = {
                    perPageItems: 10,
                    currentPage: 0,
                    totalItemsFound: 0,
                    userType: 'AccountManager'
                };


                $scope.getUsers = function ()
                {
                    Client.find($scope.listCriteria, function (res) {
                        if (res.data.message.status == 'success')
                        {
                            $scope.itemsData = res.data.message.clientsDetails;
                        }
                    });

                };

                $scope.getUsers();


                $scope.updateClient = function (id)
                {

                    $location.url("external-clients-update/" + id);



                };


                $scope.pageChanged = function ()
                {
                    $scope.getUsers();
                };

                // TO DEACTIVATE CLIENT

                
                $scope.removeManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to block client no " + ld_id + "? ");
                    if (cnfm)
                    {
                        Client.removeManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getUsers();


                        });
                    }
                };

                $scope.activateManager = function (ld_id)
                {
                    var cnfm = confirm("Are you sure you want to activate client no " + ld_id + "? ");
                    if (cnfm)
                    {
                        Client.activateManager(ld_id, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);
                            $scope.getUsers();


                        });
                    }
                };




                // FOR MODAL OF STORE LIST 


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

            }
        };
    }]);