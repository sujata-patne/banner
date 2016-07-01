<?php

require_once(APP . "Models/Campaign.php");
require_once(APP . "Models/Clients.php");
require_once(APP . "Models/BannerImage.php");
require_once(APP . "Models/Banner.php");
require_once(APP . "Models/BannerMultiselect.php");
require_once(APP . "Models/BannerPublish.php");
require_once(APP . "Models/Message.php");
require_once(APP . "Models/Multiselect.php");
require_once(APP . "Utils/Helper.php");
require_once APP.'Models/Store.php';

class BannerController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function getAction($request) {
        parent::display($request);
    }

    public function postAction($request) {
        echo "coming";
        exit;
    }

    public function getCampaignDetailsByPromoId($request) {

        $json = json_encode($request->parameters);
        $banner = new Campaign();
        $jsonObj = json_decode($json);
        $jsonMessage = $banner->validateJson($jsonObj);

        if ($jsonMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $jsonMessage);
            $this->outputError($response);
            return;
        }

        if (!$banner->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }

        if (trim($banner->promoId == '')) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_PROMO_ID);
            $this->outputError($response);
            return;
        }

        $bannerArray = $banner->getCampaignDetailsByPromoId($banner->promoId);

        if (empty($bannerArray)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD);
            $this->outputError($response);
            return;
        } else {
            /* foreach( $bannerArray as $bannerObj ) {
              $bannerObj->unsetValues( array( 'storeId') );
              } */

            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'campaignDetails' => $bannerArray);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getCampaignDetailsByStore($request) {

        $json = json_encode($request->parameters);
        $banner = new Campaign();
        $jsonObj = json_decode($json);

        $jsonMessage = $banner->validateJsonObj($jsonObj);

        if ($jsonMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $jsonMessage);
            $this->outputError($response);
            return;
        }

        if (!$banner->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }

        if (trim($banner->promoId == '')) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_PROMO_ID);
            $this->outputError($response);
            return;
        }

        if (trim($banner->storeId == '')) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_STORE_ID);
            $this->outputError($response);
            return;
        }

        $bannerObj = $banner->getCampaignDetailsByPromoIdByStoreId($banner->promoId, $banner->storeId);


        if (empty($bannerObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD);

            $this->outputError($response);
            return;
        } else {

            //$bannerObj->unsetValues( array( 'storeName', 'promoId', 'created_on', 'created_by', 'updated_on', 'updated_by' ) );

            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'campaignDetails' => $bannerObj);
            $this->outputSuccess($response);
            return;
        }
    }

    public function create($request) {

        $json = json_encode($request->parameters);
        $jsonObj = json_decode($json);


        $banner = new Banner();

        if (isset($request->parameters['id']) && !empty($request->parameters['scenario']) && $request->parameters['scenario'] == 'update') {
            $banner->scenario = "update";
            $banner->setAttributeValue('table_id', $request->parameters['id']);
            $banner->id = $request->parameters['id'];
            $msg = "Banner updated successfully.";
            
        } else {

            $banner->scenario = 'insert';
            $id = $banner->getMaxPrimaryKey('banners', 'id');
            $id++;
            $banner->setAttributeValue('table_id', $id);
            $banner->id = $id;
            $msg = "Banner created successfully.";
        }


        $banner->setAttributeValue('table_client_id', $request->parameters['clients']['id']);
        $banner->setAttributeValue('table_catalogue_detail_cd_id', $request->parameters['channels']['cd_id']);

        if (!empty($jsonObj->click_action_url)) {
            $banner->setAttributeValue('table_click_action_url', $jsonObj->click_action_url);
        }

        if (!empty($jsonObj->name)) {
            $banner->setAttributeValue('table_banner_name', preg_replace('/\s+/', ' ',$jsonObj->name));
        }

        if (!empty($jsonObj->campaign->id)) {
            $banner->setAttributeValue('table_campaign_id', $jsonObj->campaign->id);

            $campaingModel = new Campaign();
            $campaingModel = $campaingModel->findByPk('id', $jsonObj->campaign->id);

            $bannerStartDate = new DateTime($jsonObj->start_date);
            $bannerEndDate = new DateTime($jsonObj->end_date);

            $campaignStartDate = new DateTime($campaingModel['start_date']);
            $campaignEndDate = new DateTime($campaingModel['end_date']);
            $now = new DateTime();


            if ($bannerStartDate < $campaignStartDate || $bannerEndDate > $campaignEndDate) {


                $response = array("status" => "error", "status_code" => '400', 'msg' => 'Banner date should be in between  from ' . $campaignStartDate->format('d/m/Y H:i:s') . " to " . $campaignEndDate->format('d/m/Y H:i:s'));
                $this->errorLog->LogInfo('BannerController:create#'.json_encode($response));
                $this->outputError($response);
            }
        }


        $banner->setAttributeValue('table_start_date', date("y-m-d H:i:s", strtotime($request->parameters['start_date'])));
        $banner->setAttributeValue('table_end_date', date("y-m-d H:i:s", strtotime($request->parameters['end_date'])));



        if ($banner->scenario == 'insert') {
            $banner->setAttributeValue('table_created_by', Helper::getSiteUserId());
            $banner->setAttributeValue('table_created_on', Helper::getDate());
            $banner->table_is_published = 0;
        } else {
            
            
            $banner->setAttributeValue('table_created_by', $request->parameters['created_by']);
            $banner->setAttributeValue('table_created_on',date("y-m-d H:i:s", strtotime($request->parameters['created_on']) ));
            
            $banner->setAttributeValue('table_modified_by',Helper::getSiteUserId());
            $banner->setAttributeValue('table_modified_on', Helper::getDate());
            $banner->table_is_published = 0;
        }

        $result = $banner->save($banner);


        if (empty($result)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD);
            $this->errorLog->LogInfo('BannerController:create#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {

            $response = array("status" => "success", "status_code" => '200', 'item' => $result, 'msg' => $msg);
            $this->successLog->LogInfo('BannerController:create#'.json_encode(array("status" => "success", "status_code" => '200','msg' => $msg)));
            $this->outputSuccess($response);
            return;
        }
    }

    public function getBanners($request) {

       /* echo('$request->parameters--');
        print_r($request->parameters);*/
        $json = json_encode($request->parameters);
        $banner = new Banner();
        $jsonObj = json_decode($json);
        $orderBy=$request->parameters['orderBy']; //created $orderBy field for get sort column and sort order - Shivaji G.
        $sortedColumn= explode(" ", $orderBy)[0] ;
        if (!empty($request->parameters['perPageItems'])) {
            $limit = intval($request->parameters['perPageItems']);
            $offset = intval($request->parameters['perPageItems'] * $request->parameters['currentPage']);
            if ($offset != 0) {
                $offset = $offset - intval($request->parameters['perPageItems']);
            }
            //Added query for sort banners list as per  campaign_name,priority and created_updated column and get total item count -Shivaji G.
            if($sortedColumn == 'campaign_name' || $sortedColumn == 'priority'||$sortedColumn == 'created_updated'){
                $banners = $banner->findBySql("select b.*,c.campaign_name,c.priority,IF(b.created_on < b.modified_on, b.modified_on, b.created_on) as created_updated  from icon_cms.banners b inner join icon_cms.campaigns c on b.campaign_id=c.id where is_published=0 order by {$orderBy} limit {$limit} offset {$offset};");
                $rowCount =$banner->findBySql("select count(*) as totalItems from icon_cms.banners b inner join icon_cms.campaigns c on b.campaign_id=c.id  where is_published=0;");
                $bannerDetails= array('totalItemsFound'=>$rowCount['items'][0]['totalItems'], 'items'=> $banners['items']);

            }else {
                $bannerDetails = $banner->findAll([
                    'is_published' => ['=', 0]
                ], ['limit' => $limit, 'offset' => $offset, 'order' => $orderBy]//'id desc']
                );
            }
        } else {
            // if perPageItems ist empty, Added query for sort banners list as per  campaign_name,priority and created_updated column and get total item count -Shivaji G.
            if( $sortedColumn == 'campaign_name' || $sortedColumn == 'priority'||$sortedColumn == 'created_updated'){
                $banners = $banner->findBySql("select b.*,c.campaign_name,c.priority,IF(b.created_on < b.modified_on, b.modified_on, b.created_on) as created_updated  from icon_cms.banners b inner join icon_cms.campaigns c on b.campaign_id=c.id where is_published=0 order by {$orderBy};");
                $rowCount =$banner->findBySql("select count(*) as totalItems from icon_cms.banners b inner join icon_cms.campaigns c on b.campaign_id=c.id  where is_published=0;");
                $bannerDetails= array('totalItemsFound'=>$rowCount['items'][0]['totalItems'], 'items'=> $banners['items']);
            }else {
                $bannerDetails = $banner->findAll([
                    // 'is_active' => ['=', 1], //commented code because isactive is not exist in query results- Shivaji G.
                    'is_published' => ['=', 0]
                ], []);
            }
        }


        $bannersWithClient = array();
        foreach ($bannerDetails['items'] as $bannerDetail) {

            $client = new Clients();

            $bannerFullDetail = $client->findBySql("SELECT * from banners bn,clients cl, campaigns cm, catalogue_detail cd WHERE bn.client_id = cl.id AND bn.campaign_id = cm.id AND bn.catalogue_detail_cd_id = cd.cd_id AND bn.id=:id", array(':id' => $bannerDetail['id'])
            );

            $bannerImages = $client->findBySql("SELECT * FROM `banner_images_mapping` WHERE  crud_isactive = 1  AND banner_id =:id order by width asc", array(':id' => $bannerDetail['id']));


            $client = new Clients();
            $client = $client->findByPk('id', $bannerDetail['client_id']);

            $bannerDetail['clientDetail'] = $client;

            $status = "Active";

            $d1 = new DateTime();
            $d2 = new DateTime($bannerDetail['end_date']);
            if ($d1 > $d2) {
                $status = "Expired";
            } else {
                $status = "Active";
            }
            
            
            $created_updated = null; 
            
            $created_on = $bannerDetail['created_on'];
            $updated_on = $bannerDetail['modified_on'];

            if (!empty( $updated_on)) {

               $created_updated  = $updated_on;
            } else {
               $created_updated  = $created_on;
            }
            
            if(!empty($created_updated))
            {
                $bannerDetail['created_updated'] = date('d-m-Y H:i:s',strtotime($created_updated));
            }
            else
            {
                $bannerDetail['created_updated'] = "NA";
            }
            
            
            $bannerDetail['status'] = $status;


            if (!empty($bannerFullDetail['items'])) {
                $bannerDetail['details'] = $bannerFullDetail['items'][0];
            }
            if (!empty($bannerImages['items'])) {
                $bannerDetail['images'] = $bannerImages;
            }



            $bannersWithClient[] = $bannerDetail;
        }

        $bannerDetails['items'] = $bannersWithClient;

        if (empty($bannerDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('BannerController:getBanners#'.json_encode($response));
        
			$this->outputError($response);
            return;
        } else {
			
		    $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $bannerDetails);
            $this->successLog->LogInfo('BannerController:getBanners#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Banners retrieved successfully!")));
			$this->outputSuccess($response);
            return;
        }
    }

    public function getPublishBanners($request) {

        $json = json_encode($request->parameters);
        $banner = new Banner();
        $jsonObj = json_decode($json);
        
        $limit = intval($request->parameters['perPageItems']);

        $offset = intval($request->parameters['perPageItems'] * $request->parameters['currentPage']);
        if ($offset != 0) {
            $offset = $offset - intval($request->parameters['perPageItems']);
        }
        $orderBy=$request->parameters['orderBy'];
        $sortedColumn= explode(" ", $orderBy)[0] ;
       /*echo "sortedColumn---".$sortedColumn;
        echo "orderBy---".$orderBy;*/

        $banner = new Banner();

       // $bannersCount = $banner->findBySql(" SELECT *, bn.id as banner_id from

      //Modified  query to get totalItemsFound -shivaji G.

        $bannersCount = $banner->findBySql(" SELECT count(*) as totalItemsFound from
 banners bn LEFT JOIN clients cl ON bn.client_id = cl.id 
LEFT JOIN campaigns cm ON bn.campaign_id = cm.id
LEFT JOIN catalogue_detail cd ON bn.catalogue_detail_cd_id = cd.cd_id
LEFT JOIN banner_publish bp ON bn.id = bp.banner_id
LEFT JOIN icn_pub_page  ipp ON bp.icn_pub_page_pp_id = ipp.pp_id
WHERE bn.is_published = 1 and  ipp.pp_crud_isactive is null
");
        //modified query by adding created_updated ,bID,b_start_date ,b_end_date field to get published banner for sorting and pagination - Shivaji G.
        $banners = $banner->findBySql("SELECT *, bn.id as banner_id 
,bn.id as bID,bn.start_date as b_start_date,bn.end_date as b_end_date,
IF(bn.created_on < bn.modified_on, bn.modified_on, bn.created_on) as created_updated from
 banners bn LEFT JOIN clients cl ON bn.client_id = cl.id 
LEFT JOIN campaigns cm ON bn.campaign_id = cm.id
LEFT JOIN catalogue_detail cd ON bn.catalogue_detail_cd_id = cd.cd_id
LEFT JOIN banner_publish bp ON bn.id = bp.banner_id
LEFT JOIN icn_pub_page  ipp ON bp.icn_pub_page_pp_id = ipp.pp_id
WHERE bn.is_published = 1 and  ipp.pp_crud_isactive is null ORDER BY {$orderBy} 
 limit {$limit} offset {$offset}");

//     echo "offset-".$offset." limit-".$limit;
//        exit;

     // Modified code to get correct total item count - Shivaji G.
        $banners['totalItemsFound'] =$bannersCount['items'][0]['totalItemsFound'];// $bannersCount['totalItemsFound'];

//        foreach($banners['items'] as $bannerDetails )

        $bannersWithClient = array();
        foreach ($banners['items'] as $banner1) {

            $client = new Clients();

            $bannerFullDetail = $client->findBySql("SELECT * from banners bn,clients cl, campaigns cm, catalogue_detail cd WHERE bn.client_id = cl.id AND bn.campaign_id = cm.id AND bn.catalogue_detail_cd_id = cd.cd_id AND bn.id=:id", array(':id' => $banner1['banner_id'])
            );

            $bannerImages = $client->findBySql("SELECT * FROM `banner_images_mapping` WHERE  crud_isactive = 1  AND banner_id =:id order by width asc", array(':id' => $banner1['banner_id']));


            $client = new Clients();
            $client = $client->findByPk('id', $banner1['client_id']);

            $banner1['clientDetail'] = $client;

            $status = "Active";

            $d1 = new DateTime();
            $d2 = new DateTime($banner1['end_date']);
            if ($d1 > $d2) {
                $status = "Expired";
            } else {
                $status = "Active";
            }


            $created_updated = null;

            $created_on = $banner1['created_on'];
            $updated_on = $banner1['modified_on'];

         /*   if (!empty( $updated_on)) {

                $created_updated  = $updated_on;
            } else {
                $created_updated  = $created_on;
            }

            if(!empty($created_updated))
            {
                $banner1['created_updated'] = date('d-m-Y H:i:s',strtotime($created_updated));
            }
            else
            {
                $banner1['created_updated'] = "NA";
            }*/


            $banner1['status'] = $status;


            if (!empty($bannerFullDetail['items'])) {
                $banner1['details'] = $bannerFullDetail['items'][0];
            }
            if (!empty($bannerImages['items'])) {
                $banner1['images'] = $bannerImages;
            }



            $bannersWithClient[] = $banner1;
        }

        $banners['items'] = $bannersWithClient;

        


        if (empty($banners)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('BannerController:getPublishBannersPk#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $banners);
            $this->successLog->LogInfo('BannerController:getPublishBanners#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Published Banners retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }


    }
    
/*    
    public function getPublishBanners($request) {

        $json = json_encode($request->parameters);
        $banner = new Banner();
        $jsonObj = json_decode($json);

        if (!empty($request->parameters['perPageItems'])) {
            
            $limit = intval($request->parameters['perPageItems']);
            
            $offset = intval($request->parameters['perPageItems'] * $request->parameters['currentPage']);
            if ($offset != 0) {
                $offset = $offset - intval($request->parameters['perPageItems']);
            }

            $bannerDetails = $banner->findAll([
                'is_published' => ['=', 1]
                    ], ['limit' => $limit, 'offset' => $offset, 'order' => 'id desc']
            );
            
            
        } else {
            $bannerDetails = $banner->findAll([
                'is_active' => ['=', 1],
                'is_published' => ['=', 1]
                    ], []);
        }


        $bannersWithClient = array();
        foreach ($bannerDetails['items'] as $bannerDetail) {


            $client = new Clients();

            $bannerFullDetail = $client->findBySql("SELECT * from banners bn,clients cl, campaigns cm, catalogue_detail cd WHERE bn.client_id = cl.id AND bn.campaign_id = cm.id AND bn.catalogue_detail_cd_id = cd.cd_id AND bn.id=:id", array(':id' => $bannerDetail['id']));
            $bannerImages = $client->findBySql("SELECT * FROM `banner_images_mapping` WHERE  crud_isactive = 1  AND banner_id =:id", array(':id' => $bannerDetail['id']));


            $client = $client->findByPk('id', $bannerDetail['client_id']);
            $bannerDetail['clientDetail'] = $client;


            $status = "Active";

            $d1 = new DateTime();
            $d2 = new DateTime($bannerDetail['end_date']);
            if ($d1 > $d2) {
                $status = "Expired";
            } else {
                $status = "Active";
            }

            $bannerDetail['status'] = $status;



            if (!empty($bannerFullDetail['items'])) {
                $bannerDetail['details'] = $bannerFullDetail['items'][0];
            }

            if (!empty($bannerImages['items'])) {
                $bannerDetail['images'] = $bannerImages;
            }


            $bannersWithClient[] = $bannerDetail;
        }

        $bannerDetails['items'] = $bannersWithClient;

        if (empty($bannerDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $bannerDetails);
            $this->outputSuccess($response);
            return;
        }
    }

*/
    public function findOne($request) {

        $json = json_encode($request->parameters);
        $banner = new Banner();
        $jsonObj = json_decode($json);


        $bannerDetails = $banner->findBySql("SELECT * from banners bn,clients cl, campaigns cm, catalogue_detail cd WHERE bn.client_id = cl.id AND bn.campaign_id = cm.id AND bn.catalogue_detail_cd_id = cd.cd_id AND bn.id=:id", array(":id" => $jsonObj->id)
        );

        $banner = $banner->findByPk('id', $jsonObj->id);

        $campaign = new Campaign(); 
        
       $campaignDetails =  $campaign->findByPk('id',$banner['campaign_id']);
       
       
       if(!empty($campaignDetails['end_date']))
       {
           
           $banner['campaignDetails'] = $campaignDetails; 
       }
       
       
        if (!empty($bannerDetails['items'])) {

            $banner['details'] = $bannerDetails['items'][0];
            
        }



        $banner['start_date'] = date("d-m-Y H:i:s", strtotime($banner['start_date']));
        $banner['end_date'] = date("d-m-Y H:i:s", strtotime($banner['end_date']));


        $images = array();
        if (!empty($banner)) {
            $bannerImages = new BannerImage();


            $images = $bannerImages->findAll(
                array(
                'banner_id' => array('=', $banner['id']),
                'crud_isactive' => array('=', 1)
            ),array('order'=>'width asc'));

            $bannerPublish = new BannerPublish();
            
           $bannerPublish = $bannerPublish->findBySql(
                   "select * from banner_publish where banner_id=:banner_id order by id desc limit 1",
                   array(":banner_id"=>$banner['id'])
                   );
           
           
           if(!empty($bannerPublish['items']))
           {
           $bannerPublish['item'] = $bannerPublish['items'][0];
           }           
           if(!empty($bannerPublish['item']))
           {
                $pubPage = new Banner(); 
                $pubPage = $pubPage->findBySql("SELECT * FROM icn_pub_page where pp_id = :id",
                        array(':id'=>$bannerPublish['item']['icn_pub_page_pp_id'])
                        );
                

                if(!empty($pubPage['items']))
                {
                    $bannerPublish['item']['store_id'] = $pubPage['items'][0]['pp_sp_st_id'];
                    
                    $bannerPublish['item']['start_date'] = date("d-m-Y H:i:s", strtotime($bannerPublish['item']['start_date']));
                    $bannerPublish['item']['end_date'] = date("d-m-Y H:i:s", strtotime($bannerPublish['item']['end_date']));
                    
                }
            
                
                
                // to get stores banners circle multiselect data and assign when edit 
                
                $bannerMultiSelect  = new BannerMultiselect(); 
                
                $circles = $bannerMultiSelect->findBySql("SELECT * FROM `banner_multiselect` WHERE crud_isactive=1 AND  relation_type='icn_country_circle' AND banner_id = :banner_id",
                            array(':banner_id'=>$banner['id'])
                        );
                
                $operators = $bannerMultiSelect->findBySql("SELECT * FROM `banner_multiselect` WHERE crud_isactive=1 AND  relation_type='operator_country' AND banner_id = :banner_id",
                            array(':banner_id'=>$banner['id'])
                        );
                
                $selectedCircles = array(); 
                if(!empty($circles['items']))
                {
                    foreach ($circles['items'] as $circle)
                    {
                        $selectedCircles[] = array("cc_circle_id"=>$circle['relation_id']);
                    }
                }
                
                $selectedOperators = array(); 
                if(!empty($operators['items']))
                {
                    foreach ($operators['items'] as $operator)
                    {
                        $selectedOperators[] = array("id"=>$operator['relation_id']);
                    }
                }
                
                $bannerPublish['item']['selectedOperators'] = $selectedOperators; 
                $bannerPublish['item']['selectedCircles'] = $selectedCircles; 
                
                $banner['bannerPublish'] = $bannerPublish['item'];
                
                
                
                if(!empty($banner['bannerPublish']['timeslot_multiselect_group_id']))
                {
                    $multiselect = new Multiselect();
                    $timeslotsData =    $multiselect->findBySql(
                            "select * from multiselect_metadata_detail mul, catalogue_detail cd  WHERE mul.cmd_entity_detail = cd.cd_id AND mul.cmd_group_id=:cmd_group_id  AND mul.cmd_crud_isactive=1",
                            array(':cmd_group_id'=>$banner['bannerPublish']['timeslot_multiselect_group_id'])
                            );
                    
                    
                    $allTimeslots = array(); 
                   foreach( $timeslotsData['items'] as $timeslot)
                   {
                        $allTimeslots[] = array('cd_id'=>$timeslot['cd_id'],'cd_cm_id'=>$timeslot['cd_cm_id']) ;  
                   }
 
                   $banner['timeslotsSelected'] = $allTimeslots; 
                }
                else
                {
                   $banner['timeslotsSelected'] = array(); 
                }
           }
            else {
                $banner['bannerPublish'] = null; 
            }
            
            $banner['images'] = $images;
        }

        if (empty($banner)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('BannerController:findOne#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $banner);

            $this->successLog->LogInfo('BannerController:findOne#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Banner info retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function fileUpload($request) {
        $json = json_encode($request->parameters);

        $jsonObj = json_decode($json);


        $mainArray = array();

        if (!empty($_FILES['uploadedFile'])) {
            $mainArray = Helper::getFilesMaped($_FILES['uploadedFile']);

            $banner = new Banner();
            $banner = $banner->findByPk("id", $jsonObj->banner);
        } else {
            $response = array("status" => "success", "status_code" => '200', 'ms' => "No File selected");
            $this->outputError($response);
            return;
        }

        $image_extensions_allowed = array('jpg', 'jpeg', 'png', 'gif', 'bmp');


        foreach ($mainArray as $file) {
            $saved = null;
            try {
                $ext = strtolower(substr(strrchr($file['name'], "."), 1));

                if (in_array($ext, $image_extensions_allowed)) {
                    $imageSize = getimagesize($file['tmp_name']);
                    $type = explode('/', $file['type']);

                    if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        $filePath = (__DIR__) . DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."contentfiles". DIRECTORY_SEPARATOR;
                    } else {
 
                        $filePath = (__DIR__) . DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."contentfiles". DIRECTORY_SEPARATOR;
                    }

                    $fileName = $banner['banner_name'] . "_" . rand() . "_" . $file['name'];
                    $saved = move_uploaded_file($file['tmp_name'], $filePath . $fileName);


                    $bannerImage = new BannerImage();
                    $bannerImage->table_image_name = $fileName;
                    $id = $bannerImage->getMaxPrimaryKey('banner_images_mapping', 'id');
                    $id++;
                    $bannerImage->table_id = $id;
                    $bannerImage->id = $id;
                    $bannerImage->table_crud_isactive = 1;

                    $bannerImage->table_width = $imageSize[0];
                    $bannerImage->table_height = $imageSize[1];

                    $bannerImage->table_banner_id = $jsonObj->banner;




                    if ($bannerImage->scenario == 'insert') {
                        $bannerImage->setAttributeValue('table_created_by', Helper::getSiteUserId());
                        $bannerImage->setAttributeValue('table_created_on', Helper::getDate());
                    } else {
                        
                        $bannerImage->setAttributeValue('table_modified_by', Helper::getSiteUserId());
                        $bannerImage->setAttributeValue('table_modified_on', Helper::getDate());
                    }
                    $result = $bannerImage->save($bannerImage);

                    if ($result) {
                        
                    }
                } else {

                    $response = array("status" => "error", "status_code" => '401', 'msg' => "Banner data updated successfuly but couple of files are unsupported format. Please check your selected files again.");
                    $this->errorLog->LogInfo('BannerController:fileUpload#'.json_encode($response));
                    $this->outputError($response);
                    return;
                }
            } catch (Exception $e) {
                $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not upload selected files.");
                $this->errorLog->LogInfo('BannerController:fileUpload#'.json_encode($response));
                $this->outputError($response);
                return;
            }
        }


        if (empty($saved)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not upload selected files.");
            $this->errorLog->LogInfo('BannerController:fileUpload#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200');
            $this->successLog->LogInfo('BannerController:fileUpload#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"File uploaded successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function removeBannerImage($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $result = null;
        if (!empty($jsonObj->id)) {
            $result = $bannerImage->updateAll(array('id' => array(
                    '=', $jsonObj->id,
                )), array('crud_isactive' => 0));
        } else if (!empty($jsonObj->banner_id)) {
            $result = $bannerImage->updateAll(array('banner_id' => array(
                    '=', $jsonObj->banner_id,
                )), array('crud_isactive' => 0));
        } else {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not remove image please contact admin.");
            $this->outputSuccess($response);
            return;
        }


        if (empty($result)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Banner image removed successfully.");
            $this->outputSuccess($response);
            return;
        }
    }

    public function getPagesBySite($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $sitePages = $bannerImage->findBySql("SELECT * FROM icn_pub_page where pp_sp_st_id=:id AND pp_crud_isactive is null", array(':id' => $jsonObj->st_id)
        );

        if (empty($sitePages)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'pageDetails' => $sitePages);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getPlacesByPage($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $sitePages = $bannerImage->findBySql("SELECT * FROM `icn_pub_page_portlet` where ppp_type=1 AND ppp_is_active=1 AND ppp_pp_id =:id  AND ppp_crud_isactive is null", array(':id' => $jsonObj->pp_id)
        );



        if (empty($sitePages)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'placeDetails' => $sitePages);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getOperatorsList($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $operators = $bannerImage->findBySql("SELECT * FROM operator_country;");

        if (empty($operators)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->errorLog->LogInfo('BannerController:getOperatorsList#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'operatorDetails' => $operators);
            $this->successLog->LogInfo('BannerController:getOperatorsList#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.")));
            $this->outputSuccess($response);
            return;
        }
    }
    public function getTimeslotList($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $operators = $bannerImage->findBySql("SELECT cd_id,cd_cm_id,cd_name,cd_display_name FROM `catalogue_detail` cd, catalogue_master cm where cd.cd_cm_id = cm.cm_id AND cm.cm_name ='timeslot';");

        if (empty($operators)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'timeslotDetails' => $operators);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getCircleList($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $operators = $bannerImage->findBySql("SELECT * FROM icn_country_circle;");

        if (empty($operators)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->errorLog->LogInfo('BannerController:getCircleList#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'circleDetails' => $operators);
            $this->successLog->LogInfo('BannerController:getCircleList#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function getHandsetList($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage(array('db' => 'SITE_USER'));

        $jsonObj = json_decode($json);

        $handsets = $bannerImage->findBySql("SELECT distinct(dc_make) as handset,dc_id FROM device_compatibility group by handset;");

        if (empty($handsets)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->errorLog->LogInfo('BannerController:getHandsetList#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'handsetDetails' => $handsets);
            $this->successLog->LogInfo('BannerController:getHandsetList#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function getHandsetFilter($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage(array('db' => 'SITE_USER'));

        $jsonObj = json_decode($json);
        
       
        $handsets = $bannerImage->findBySql("SELECT * FROM device_compatibility");

        if (empty($handsets)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'handsetDetails' => $handsets);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getOperatingSystemList($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage(array('db' => 'SITE_USER'));
        $jsonObj = json_decode($json);

        $operators = $bannerImage->findBySql("SELECT distinct(dc_OS) as operatingSystem,dc_id FROM device_compatibility group by operatingSystem;");

        if (empty($operators)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data");
            $this->errorLog->LogInfo('BannerController:getOperatingSystemList#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'operatingSystemDetails' => $operators);
            $this->successLog->LogInfo('BannerController:getOperatingSystemList#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function getHandsetGroup($request) {

        $json = json_encode($request->parameters);
        $bannerImage = new BannerImage();
        $jsonObj = json_decode($json);

        $operators = $bannerImage->findBySql("SELECT * FROM `content_handset_group_reference` where chgr_crud_isactive =1 ;");

        if (empty($operators)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => "Could not load data",'handsetGroupDetails'=>array());
            $this->errorLog->LogInfo('BannerController:getHandsetGroup#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.", 'handsetGroupDetails' => $operators);
            $this->successLog->LogInfo('BannerController:getHandsetGroup#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' => "Found data successfully.")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function BannerPublish($request) {

	 echo 'BannerPublish';
	 print_r ($request);
        $json = json_encode($request->parameters);
        $jsonObj = json_decode($json);
        
        $bannerPublish = new BannerPublish();

        
        if (isset($request->parameters['id']) &&  !empty($request->parameters['scenario']) && $request->parameters['scenario'] == 'update') {
            $bannerPublish->scenario = "update";
            $bannerPublish->setAttributeValue('table_id', $request->parameters['id']);
            $bannerPublish->id = $request->parameters['id'];
            $bannerPublish->table_id = $request->parameters['id'];

            $msg = "Published banner updated successfully.";
            
            $oldBanner = $bannerPublish->findByPk('id',$request->parameters['id']);
                    
            $multiselect = new Multiselect(); 
        
            $multiselect->updateAll(array(
                'cmd_group_id'=>array('=',$oldBanner['timeslot_multiselect_group_id'])
            ),
                    array('cmd_crud_isactive'=>0)
            );
            

            
            
            
        } else {

            $bannerPublish->scenario = 'insert';
            $id = $bannerPublish->getMaxPrimaryKey('banner_publish', 'id');
            $id++;
            $bannerPublish->setAttributeValue('table_id', $id);
            $bannerPublish->id = $id;
            $msg = "Banner published successfully.";
        }


        $bannerPublish->setAttributeValue('table_user_type', $jsonObj->user_type);

//        $bannerPublish->setAttributeValue('table_', $jsonObj->site->st_id);

        $bannerPublish->setAttributeValue('table_icn_pub_page_pp_id', $jsonObj->page->pp_id);
        $bannerPublish->setAttributeValue('table_banner_id', $jsonObj->banner_id);
        $bannerPublish->setAttributeValue('table_is_active', 1);

        $bannerPublish->setAttributeValue('table_start_date', date("y-m-d H:i:s", strtotime($jsonObj->start_date)));
        $bannerPublish->setAttributeValue('table_end_date', date("y-m-d H:i:s", strtotime($jsonObj->end_date)));
        
        $bannerMultipleUpdate = new BannerMultiselect(); 
        $bannerMultipleUpdate->updateAll(                    
                   array( 'banner_id'=>array('=',$jsonObj->banner_id),
                          'relation_type'=>array('=','operator_country')
                       ),
                       array('crud_isactive'=>0)

                );
        
            $multiselect = new Multiselect(); 
            
            $group_id = $multiselect->getMaxPrimaryKey('multiselect_metadata_detail', 'cmd_group_id');

            if(!empty($jsonObj->timeslot))
            {
                foreach($jsonObj->timeslot as $timeslot)
                {
                    $multiselect = new Multiselect(); 
                    $id = $multiselect->getMaxPrimaryKey('multiselect_metadata_detail', 'cmd_id');
                    $id++;
                    $multiselect->table_cmd_id = $id; 
                    $multiselect->table_cmd_group_id = $group_id; 
                    $multiselect->table_cmd_entity_type = $timeslot->cd_cm_id; 
                    $multiselect->table_cmd_entity_detail = $timeslot->cd_id; 
                    $multiselect->table_cmd_crud_isactive = 1; 
                    $multiselectData =   $multiselect->save($multiselect);

                }
                
                $bannerPublish->setAttributeValue('table_timeslot_multiselect_group_id',$multiselectData->table_cmd_group_id) ;

            }

        
        if(!empty($jsonObj->operator))
        {
            foreach($jsonObj->operator as $operator)
            {
                $bannerMultiple = new BannerMultiselect();

                $id = $bannerMultiple->getMaxPrimaryKey('banner_multiselect', 'id');
                $id++;
                $bannerMultiple->table_id = $id; 
                $bannerMultiple->table_banner_id = $jsonObj->banner_id; 
                $bannerMultiple->table_relation_type = 'operator_country';
                $bannerMultiple->table_relation_id = $operator->id;

                $bannerMultiple->table_created_by = Helper::getSiteUserId();
                $bannerMultiple->table_crud_isactive = 1;

                $bannerMultiple->setAttributeValue('table_created_on',Helper::getDate());
                $bannerMultiple->save($bannerMultiple); 

            }
        }
//        foreach($jsonObj->circle as $circle)
//        {
//            $bannerMultiple = new BannerMultiselect(); 
//            $id = $bannerMultiple->getMaxPrimaryKey('banner_multiselect', 'id');
//            $id++;
//            $bannerMultiple->table_id = $id; 
//            $bannerMultiple->table_banner_id = $jsonObj->banner_id; 
//            $bannerMultiple->table_relation_type = 'icn_country_circle';
//            $bannerMultiple->table_relation_id = $circle->cc_circle_id;
//            $bannerMultiple->table_created_by = Helper::getSiteUserId();
//            $bannerMultiple->table_created_on = Helper::getDate();
//            $bannerMultiple->table_crud_isactive = 1;
//            $res = $bannerMultiple->save($bannerMultiple); 
//        }
        
            $bannerMultipleUpdate = new BannerMultiselect(); 
            $bannerMultipleUpdate->updateAll(                    
                   array( 'banner_id'=>array('=',$jsonObj->banner_id),
                          'relation_type'=>array('=','icn_country_circle')
                       ),
                       array('crud_isactive'=>0)

                );
        
        foreach($jsonObj->circle as $circle)
        {
            $bannerMultiple = new BannerMultiselect(); 
            $id = $bannerMultiple->getMaxPrimaryKey('banner_multiselect', 'id');
            $id++;
            $bannerMultiple->table_id = $id; 
            $bannerMultiple->table_banner_id = $jsonObj->banner_id; 
            $bannerMultiple->table_relation_type = 'icn_country_circle';
            $bannerMultiple->table_relation_id = $circle->cc_circle_id;
            $bannerMultiple->table_created_by = Helper::getSiteUserId();
            $bannerMultiple->table_created_on = Helper::getDate();
            $bannerMultiple->table_crud_isactive = 1;

            $res = $bannerMultiple->save($bannerMultiple); 

        }



        $banner1 = $bannerPublish->findBySql(
            "select * from banners where id=:banner_id order by id desc limit 1",
            array(":banner_id"=>$request->parameters['banner_id'])
        );

        if($jsonObj->deviceBroser == 'group' && !empty($jsonObj->handset_group))
        {
                
            $bannerPublish->setAttributeValue('table_content_handset_group_id', $jsonObj->handset_group->chgr_group_id);
        
        }
        $bannerPublish->setAttributeValue('table_created_by',Helper::getSiteUserId());
        $bannerPublish->setAttributeValue('table_created_on', $banner1['items'][0]['created_on']);
        $bannerPublish->setAttributeValue('table_icn_pub_page_portlet_ppp_id',$jsonObj->place->ppp_id);
        $bannerPublish->setAttributeValue('table_modified_on',date('Y-m-d H:i:s'));
        $bannerPublish->setAttributeValue('table_modified_by',Helper::getSiteUserId());

        $result = $bannerPublish->save($bannerPublish);

        if (empty($result)) {
            $response = array("status" => "error", "status_code" => '400', 'msg' => " Could not publish banner.");
            $this->errorLog->LogInfo('BannerController:BannerPublish#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {

                $banner = new Banner(); 
                $banner->updateByPk($jsonObj->banner_id,array(
                   'is_published'=>1 
                ));
                
            $response = array("status" => "success", "status_code" => '200', 'item' => $result, 'msg' => $msg);
            $this->successLog->LogInfo('BannerController:BannerPublish#'.json_encode(array("status" => "success", "status_code" => '200', 'msg' => $msg)));
            $this->outputSuccess($response);
            return;
        }
    }

    public function downloadBannerList($request) {

        $json = json_encode($request->parameters);
        $jsonObj = json_decode($json);

        $model = new Banner();
        $banners = $model->findBySql("SELECT bn.id, campaign_name, banner_name, bn.start_date as start_date, bn.end_date,cp.priority,bn.created_on,bn.modified_on FROM banners bn LEFT JOIN campaigns cp ON bn.campaign_id = cp.id");
        
        try {

            $filePath =  (__DIR__)."/../../../app/downloads/";
            $filename = "banner_list" . ".xls";
            
            
            $fp = fopen($filePath.$filename, "w") or die("Unable to open file!");

            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='icon_cms' AND TABLE_NAME='banners'";
            
            
            $headers = $model->findBySql($query);

            $headers = array("Id","CampaingName",'Banner Name','Start Date','End Date','Priority',"Added/Updated On");
            
            $headerNames = array();
            foreach ($headers as $header) {
                $headerNames[] = $header;
            }


            fputcsv($fp, $headerNames);

            
            $newbannerList = array(); 
            foreach($banners['items'] as $item)
            {
                    $created_on = $item['created_on'];
                    $updated_on = $item['modified_on'];
            

                    if(empty($updated_on))
                    {
                        $created_updated  = $created_on;
                    }
                    else {
                        $created_updated  = $updated_on;
                    }
                    
                    
                    if(!empty($created_updated))
                    {
                        $item['created_updated'] = date('d-m-Y H:i:s',  strtotime($created_updated));
                    }
                    else
                    {
                        $item['created_updated'] = "NA";
                    }

                    unset($item['created_on']);
                    unset($item['modified_on']);
                    
                $newbannerList[] = $item;
                
                
            }
            
            
            
            foreach ($newbannerList as $banner) {
                fputcsv($fp, $banner);
            }

            fclose($fp);

            if( file_exists($filePath.$filename))
                    
            {
                          

                $response = array("status" => "success", "status_code" => '200', 'msg' => "file created successfully");
                $this->outputSuccess($response);
                return;
            }
            else {

           
                    $response = array("status" => "error", "status_code" => '400', 'msg' => "file could not created");
                    $this->outputSuccess($response);
                    return;

                }
    
            

        } catch (Exception $ex) {
            $this->errorLog->LogInfo('BannerController:downloadBannerList#'.json_encode(array("status" => "error", "status_code" => '400', 'msg' => " Could not download BannerList.")));
            
            $response = array("status" => "error", "status_code" => '400', 'msg' => "file could not created");
            $this->outputSuccess($response);
            return;
        }
    }
    
    
    
    /*
     * 
     * get this page 
     * 
     */
        public function getStoresByCampaign($request) {

            $json = json_encode($request->parameters);
            $jsonObj = json_decode($json);

            $store = new Store();
            
            $banner = new Banner(); 
            
            $banner = $banner->findByPk('id',$jsonObj->banner_id); 
            
                
            
            if (!empty($banner['campaign_id']))
            {
                
                $stores = $store->findBySql(
                        "SELECT * FROM `campaign_store_mapping` csm, icn_store str  WHERE str.st_id = csm.icn_store_st_id  AND csm.is_active =1 AND campaign_id=:campaign_id",
                        array(':campaign_id'=>$banner['campaign_id'])
                        );                
                $response = array("status" => "success", "status_code" => '200', 'storeData' => $stores['items']);
                $this->outputSuccess($response);
                return;
             }

        }
        
                
        
        
        public function bannerRemove($request) {

            $json = json_encode($request->parameters);
            $jsonObj = json_decode($json);
            $banner = new BannerPublish(); 
            
            try {
                
               $result =  $banner->updateByPk($jsonObj->id,array(
                   'is_active'=>0 
                ));            
               
               
               if(!empty($request))
               {
                   
                    $response = array("status" => "success", "status_code" => '200','msg'=>'Banner place unmapped successfully.');
                    $this->outputSuccess($response);
                    return;
                   
               }
               else
               {
                     $response = array("status" => "error", "status_code" => '400','msg'=>'Banner could not removed.');
                    $this->outputSuccess($response);
                    return;
               }
            } catch (Exception $ex) {
                
                $response = array("status" => "error", "status_code" => '400','msg'=>'Banner could not removed.');
                $this->outputSuccess($response);
                return;
            }        

        }

}

?>
