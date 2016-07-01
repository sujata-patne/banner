directives.directive('bannerFormGrid', ['$appLocation', 'Store', 'Client', 'Campaign', 'Banner', 'alertService', '$location', '$route','UserService', function ($appLocation, Store, Client, Campaign, Banner, alertService, $location, $route,UserService) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/banner_form_grid'),
            controller: function ($scope)
            {              
                $scope.grid = {};
                $scope.banner = {};
                $scope.banner.scenario = 'insert';
                $scope.grid.title = "Banner Management";
                $scope.submitted = false;


                $scope.refresh = function() 
                {

                        Store.find({}, function (res) {

                            $scope.storeList = res.message.storeDetails;

                            if (typeof $scope.id != 'undefined' && $scope.id != null)
                            {

                                Banner.findOne({id: $scope.id}, function (res)
                                {
                                    if (res.data.message.status == 'success')
                                    {
                                        $scope.banner = res.data.message.clientsDetails;

                                        $scope.banner.name = $scope.banner.banner_name;
                                        $scope.banner.clients = {id: $scope.banner.client_id};

                                        Campaign.getDeliveryChannel($scope.banner.clients, function (res) {

                                            $scope.storesList = res.data.message.clientsDetails.stores;
                                            $scope.banner.channels = {cd_id: $scope.banner.catalogue_detail_cd_id};


                                            Campaign.findAllByClient($scope.banner, function (res) {
                                                

                                                $scope.campaignList = res.data.message.camapigns;
                                                $scope.banner.campaign = {id: $scope.banner.campaign_id};

                                                $scope.banner.scenario = "update";

                                            });
                                        });
                                    }
                                });
                            }
                            else
                            {
                                $scope.banner = {scenario: 'insert'};
                                $scope.banner.scenario = "insert";
                            }


                        });

                Client.findAll({}, function (res) {
                    $scope.clientList = res.data.message.clientsDetails.items;
                });

                };


                $scope.refresh();
                $scope.saveBanner = function (form)
                {
                    form.submitted = true;
                    
                    
                    if (form.$invalid) {
                        angular.forEach(form.$error, function (field) {
                            angular.forEach(field, function(errorField){
                                errorField.$setTouched();
                            });
                        });
                    }
                    
                    
                    if (form.$valid)
                    {
                        
                      Banner.save($scope.banner, function (res) {

                                if (res.data.message.status == 'success')
                                {
                                    $scope.uploadFiles(res.data.message.item, function (img_res) {

                                        img_res = JSON.parse(img_res);
                                        if (img_res.message.status == 'success')
                                        {
                                            alertService.add(res.data.message.status, res.data.message.msg);
                                            
                                            if($scope.banner.scenario == 'insert')
                                            {
                                                $scope.banner = {};
                                                form.$setPristine();
                                                form.submitted = false;
                                                $location.url('banner-view/'+res.data.message.item.id+'?alert=true');
                                            }
                                            else
                                            {
                                                $scope.refresh();
                                                $location.url('banner-view/'+$scope.banner.id+'?alert=true');
                                            }                                        
                                        }
                                        else
                                        {
                                            
                                            if(img_res.message.status_code == 401)
                                            {
                                                alertService.add(img_res.message.status, img_res.message.msg);
                                            }
                                            else
                                            {
                                                alertService.add(img_res.message.status, img_res.message.msg);
                                            }               
                                            
                                            angular.element("input[type='file']").val(null);
 
                                            $scope.refresh();
                                            
                                        }
                                    });
                                }
                                else
                                {
                                    alertService.add(res.data.message.status, res.data.message.msg);
                                }

                            });
                       

                    }


                };



                $scope.clientChange = function ()
                {
                    if (typeof $scope.banner.clients != 'undefined' && $scope.banner.clients != null)
                    {

                        Campaign.getDeliveryChannel($scope.banner.clients, function (res) {

                            $scope.storesList = res.data.message.clientsDetails.stores;

                        });
                    }
                    else
                    {
                        $scope.storesList = null;
                    }
                };



                $scope.changeDeliveryChannel = function ()
                {
                    if (typeof $scope.banner.channels != 'undefined' && $scope.banner.channels != null)
                    {
                        Campaign.findAllByClient($scope.banner, function (res) {


                            $scope.campaignList = res.data.message.camapigns;



                        });
                    }
                    else
                    {
                        $scope.campaignList = null;
                    }
                };


                $scope.campaignChange = function ()
                {
                    $scope.banner.start_date = $scope.banner.campaign.campaign_start_date;
                    $scope.banner.end_date = $scope.banner.campaign.campaign_end_date;
                };

                $scope.reset = function (form)
                {
                    form.$setPristine();
                    form.submitted = false;
                    $scope.banner.scenario = 'insert';
                    alertService.reset();
                    $location.url('banners');
                    $route.reload();
                };





                $scope.goToBanners = function ()
                {
                    $location.url("banner-list");
                };


                $scope.allfiles = [];


                // GET THE FILE INFORMATION.
                $scope.getFileDetails = function (e) {

                    $scope.files = [];
                    $scope.$apply(function () {
                        // STORE THE FILE OBJECT IN AN ARRAY.
                        for (var i = 0; i < e.files.length; i++) {
                            $scope.files.push(e.files[i]);
                        }

                        $scope.$watch($scope.files, function (value) {

                            $scope.allfiles = $scope.files;

                        });
                    });
                };


                $scope.validateUploadFiles = function(files)
                {
                    if( typeof files !== 'undefined' && files.length > 10)
                    {
                        alertService.add('error',"Please select less than or 10 images at a time." );
                        return false; 
                    }
                    else
                    {
                        return true; 
                    }

                    
                };

                // NOW UPLOAD THE FILES.
                $scope.uploadFiles = function (banner, callback) {

                if($scope.validateUploadFiles($scope.files))
                {
                                try{

                                    //FILL FormData WITH FILE DETAILS.
                                    var data = new FormData();

                                    data.append("banner", banner.id);


                                    angular.forEach($scope.files, function (file) {
                                        data.append("uploadedFile[]", file);

                                    });


                                    // ADD LISTENERS.
                                    var objXhr = new XMLHttpRequest();
                                    objXhr.addEventListener("progress", updateProgress, false);
                                    objXhr.addEventListener("load", transferComplete, false);
                                   
                                    

                                    objXhr.onreadystatechange = function () {
                                        if (objXhr.readyState == 4 && objXhr.status == 200) {
                                            callback(objXhr.responseText);
                                        }
                                    };


                                    // SEND FILE DETAILS TO THE API.
                                    objXhr.open("POST", CONFIG.getApiUrl('Banner/fileUpload'));

var userLoogedIn = UserService.getCurrentUser();

if(typeof userLoogedIn.access_token != 'undefined')
{

                                    objXhr.setRequestHeader("Accept", userLoogedIn.access_token);
}

                                    objXhr.send(data);

                                }catch (err) {

console.log(err);
                                    alertService.add('error',"Could not upload file." );

                                }
                            }
                            else
                            {
                                
                                alertService.add('error',"Please select less than 10 images at a time." );

                                
                            }

                };

                // UPDATE PROGRESS BAR.
                function updateProgress(e) {
//                    if (e.lengthComputable) {
//                        document.getElementById('pro').setAttribute('value', e.loaded);
//                        document.getElementById('pro').setAttribute('max', e.total);
//                    }
                }

                // CONFIRMATION.
                function transferComplete(e) {
//            alert("Files uploaded successfully.");
                }





                $scope.wallpaperfileuploader = function () {
                    $scope.wallpapererror = false;

                    $scope.WallPaperFiles = [];
                    if ($scope.wallpaperfile) {
                        if ($scope.wallpaperfile.length <= 3) {
                            try {
                                imageloop(0);
                                function imageloop(cnt) {
                                    var i = cnt;
                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                        var new_file = new Image();
                                        new_file.src = e.target.result;
                                        new_file.onload = function () {
                                            if ((this.height == 1280 && this.width == 1280) || (this.height == 1280 && this.width == 720) || (this.height == 720 && this.width == 1280)) {
                                                cnt = cnt + 1;
                                                var height = this.height;
                                                var width = this.width;
                                                var match = _.find($scope.Templates, function (val) {
                                                    return ((val.width == width) && (val.height == height));
                                                })
                                                if (match) {
                                                    $scope.WallPaperFiles.push({file: $scope.wallpaperfile[i], type: 'Wallpaper', ct_group_id: match.ct_group_id, cm_id: $scope.MetaId, width: this.width, height: this.height, other: null})
                                                }
                                                if (!(cnt == $scope.wallpaperfile.length)) {
                                                    imageloop(cnt);
                                                }
                                                else {
                                                    $scope.wallpapererror = false;
                                                }
                                            }
                                            else {
                                                $scope.wallpapererror = true;
                                                $scope.wallpapererrormessage = "Invalid Dimension In " + $scope.wallpaperfile[i].name + ".";
                                                toastr.error($scope.wallpapererrormessage);
                                            }
                                        }
                                    }
                                    reader.readAsDataURL($scope.wallpaperfile[i]);
                                }
                            }
                            catch (err) {
                                $scope.wallpapererror = true;
                                $scope.wallpapererrormessage = "Invalid Image Format.";
                                toastr.error($scope.wallpapererrormessage);
                            }
                        }
                        else {
                            $scope.wallpapererror = true;
                            $scope.wallpapererrormessage = "Maximum Three base Image file upload at a time.";
                            toastr.error($scope.wallpapererrormessage);
                        }
                    }
                };


                
                /*
                 *  This is to remove banner image from update form by clicking on cross sign showing at top of image
                 */
                $scope.removeBannerImage = function (id)
                {
                    var conf = confirm("Are you sure want to remove banner image? ");
                    
                    if(conf)
                    {
                        Banner.removeById({id:id}, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);

                              $scope.refresh();
                        });
                    }

                };
                
                /*
                 *  This is to remove banner image from update form by clicking on cross sign showing at top of image
                 */
                $scope.removeAllBaners = function (id)
                {
                    
                    var conf = confirm("Are you sure want to remove banner image? ");
                    
                    if(conf)
                    {
                    
                        Banner.removeById({banner_id:id}, function (res) {

                            alertService.add(res.data.message.status, res.data.message.msg);

                              $scope.refresh();
                        });
                    }

                };

            }
        };
    }]);
