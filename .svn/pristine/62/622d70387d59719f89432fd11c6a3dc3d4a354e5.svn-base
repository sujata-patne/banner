<?php
require_once(APP."Models/Campaign.php");
require_once(APP."Models/Clients.php");
require_once(APP."Models/ClientStores.php");
require_once(APP."Models/CampaignStores.php");
require_once(APP."Models/Message.php");

class CampaignController extends BaseController {

	public function __construct() {
		parent::__construct();
	}
	
    public function getAction($request){
        parent::display($request);
    }

    public function postAction( $request ) {
       echo "coming";exit;
    }

    public function getCampaignDetailsByPromoId( $request ) {
        
        $json = json_encode( $request->parameters );
        $campaign = new Campaign();
      	$jsonObj = json_decode($json);
        $jsonMessage = $campaign->validateJson($jsonObj);
            
        if ($jsonMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $jsonMessage);
            $this->outputError($response);
            return;
        }
        
        if (!$campaign->setValuesFromJsonObj($jsonObj)) {
        	$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
        	$this->outputError($response);
        	return;
        }
        
        if (trim( $campaign->promoId == '') ) {
        	$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_PROMO_ID );
        	$this->outputError($response);
        	return;
        }
        
        $campaignArray = $campaign->getCampaignDetailsByPromoId( $campaign->promoId );
        
        if( empty( $campaignArray ) ){
        	$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD );
        	$this->outputError($response);
        	return;
        }else{
        	/* foreach( $campaignArray as $campaignObj ) {
        		$campaignObj->unsetValues( array( 'storeId') );
        	} */
        
        	$response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'campaignDetails' => $campaignArray );
        	$this->outputSuccess($response);
        	return;
        }
    }
    
    public function getCampaignDetailsByStore( $request ) {
    
    	$json = json_encode( $request->parameters );
        $campaign = new Campaign();
      	$jsonObj = json_decode($json);
      	
        $jsonMessage = $campaign->validateJsonObj($jsonObj);
    
    	if ($jsonMessage != Message::SUCCESS) {
    		$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $jsonMessage);
    		$this->outputError($response);
    		return;
    	}
    	
    	if (!$campaign->setValuesFromJsonObj($jsonObj)) {
    		$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
    		$this->outputError($response);
    		return;
    	}
    	
    	if (trim( $campaign->promoId == '') ) {
    		$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_PROMO_ID );
    		$this->outputError($response);
    		return;
    	}
    	
    	if ( trim( $campaign->storeId == '' ) ) {
    		$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_STORE_ID );
    		$this->outputError($response);
    		return;
    	}
    	
    	$campaignObj = $campaign->getCampaignDetailsByPromoIdByStoreId( $campaign->promoId, $campaign->storeId );
    	
    	
    	if( empty( $campaignObj ) ){
    		$response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD );
    		$this->outputError($response);
    		return;
    	}else{
    	
    		//$campaignObj->unsetValues( array( 'storeName', 'promoId', 'created_on', 'created_by', 'updated_on', 'updated_by' ) );
    	
    		$response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'campaignDetails' => $campaignObj );
            $this->successLog->LogInfo('CampaignController:getClients#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Clients retrieved successfully!")));
    		$this->outputSuccess($response);
    		return;
    	}
    }
    
    
    
    public function create($request) {

        $json = json_encode($request->parameters);
        $jsonObj = json_decode($json);
        
        
        $campaign = new Campaign();
        
        if (isset($request->parameters['id']) && $request->parameters['scenario'] == 'update') {
            $campaign->scenario = "update";
            $campaign->setAttributeValue('table_id', $request->parameters['id']);
            $campaign->id = $request->parameters['id'];
            
            $campaign->setAttributeValue('table_modified_by',Helper::getSiteUserId()); 
            $campaign->setAttributeValue('table_modified_on',Helper::getDate()); 
            
            $campaign->setAttributeValue('table_created_on', date("y-m-d H:i:s",strtotime( $request->parameters['created_on']))); 
            
            
            $campaign->setAttributeValue('table_created_by',$request->parameters['created_by']); 
            
            $msg = "Campaign updated successfully.";
            
        } else {
            
            $campaign->setAttributeValue('table_created_by',Helper::getSiteUserId()); 
            $campaign->setAttributeValue('table_created_on',Helper::getDate()); 
            
            $campaign->scenario = 'insert';
            $id = $campaign->getMaxPrimaryKey('campaigns', 'id');
            $id++;
            $campaign->setAttributeValue('table_id', $id);
            $campaign->id = $id;
            $msg = "Campaign created successfully.";
            
        }
     
        if(!empty($request->parameters['clients']['id']))
        {
            $clientCheck  = new Clients() ;
            
            $clientCheck = $clientCheck->findByPk('id',$request->parameters['clients']['id']);
            
            $d1 = new DateTime($clientCheck['expired_on']);
            
            $d2 = new DateTime($request->parameters['end_date']);
            
            if($d1 < $d2)
            {
                
                $response = array("status" => "error", "status_code" => '400', 'msg' =>" Start and End date should be within limit of Client limits.");
                $this->errorLog->LogInfo('UserController:findByPk#'.json_encode($response));
    		$this->outputError($response);
    		return;
                
            }
            
        }

        $campaign->setAttributeValue('table_client_id',$request->parameters['clients']['id']); 
        $campaign->setAttributeValue('table_catalogue_detail_cd_id',$request->parameters['channels']['cd_id']); 
        
        if(!empty($request->parameters['instructions']))
        {
            $campaign->setAttributeValue('table_instruction',$request->parameters['instructions']); 
        }
        
            $campaign->setAttributeValue('table_campaign_name',preg_replace('/\s+/', ' ',$request->parameters['campaign_name'])); 

        if(!empty($request->parameters['total_impressions']) && $request->parameters['total_impressions'] > 0)
        {
            $campaign->setAttributeValue('table_total_impression',$request->parameters['total_impressions']); 
        }
        else
        {
            $campaign->setAttributeValue('table_total_impression',100000000); 
        }    
        
        if(!empty($request->parameters['total_clicks']) && $request->parameters['total_clicks'] > 0)
        {
            $campaign->setAttributeValue('table_total_clicks',$request->parameters['total_clicks']); 
        }
        else
        {
            $campaign->setAttributeValue('table_total_clicks',10000000); 
        }
        
        if(!empty($request->parameters['description']))
        {
            $campaign->setAttributeValue('table_description',$request->parameters['description']); 
        }
        $campaign->setAttributeValue('table_priority',$request->parameters['priority']['value']); 
        
        $campaign->setAttributeValue('table_start_date',date("y-m-d H:i:s",strtotime($request->parameters['start_date']))); 
        $campaign->setAttributeValue('table_end_date',date("y-m-d H:i:s",strtotime($request->parameters['end_date']))); 
        
        
        
        
        
        
        $result =   $campaign->save($campaign);
 
        
       	if( empty( $result ) ){
    		$response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_CAMPAIGN_LOAD );
            $this->errorLog->LogInfo('CampaignController:create#'.json_encode($response));
    		$this->outputError($response);
    		return;
    	}else{
            // HERE This stores stores into campaing_s
            
            $campStoreUpdate = new CampaignStores();
            
            $campStoreUpdate->updateAll(array('campaign_id' => array(
                        '=', $result->id
                    )), array('is_active' => 0));

            
            if(!empty($request->parameters['preferred_store']))
            {       
                foreach($request->parameters['preferred_store'] as $pstore)
                {
                    $campaignStore = new CampaignStores();
                    $campaignStore->setAttributeValue('table_campaign_id',$result->id);
                    $campaignStore->setAttributeValue('table_icn_store_st_id',$pstore['st_id']);
                    $ld_id = $campaignStore->getMaxPrimaryKey('campaign_store_mapping', 'id');
                    $ld_id++;
                    $campaignStore->setAttributeValue('table_id', $ld_id);
                    $campaignStore->id = $ld_id; 
                    
                    if($campaignStore->scenario == 'insert')
                    {
                        $campaignStore->setAttributeValue('table_created_by',Helper::getSiteUserId()); 
                        $campaignStore->setAttributeValue('table_created_on',Helper::getDate()); 
                    }
                    else {

                        $campaignStore->setAttributeValue('table_modified_by',Helper::getSiteUserId()); 
                        $campaignStore->setAttributeValue('table_modified_on',Helper::getDate());                
                    }

                    
                    $campaignStore->save($campaignStore);
                }
            }
            
                //$campaignObj->unsetValues( array( 'storeName', 'promoId', 'created_on', 'created_by', 'updated_on', 'updated_by' ) );
    	
    		$response = array("status" => "success", "status_code" => '200', 'campaignDetails' => '' ,'msg'=>$msg);
            $this->successLog->LogInfo('CampaignController:create#'.json_encode( $response));
    		$this->outputSuccess($response);
    		return;
    	}
    }
        
    
    
    
    /*
     * get stores - delivery channels of selected client
     */
    
    public function getDeliveryChannel($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        $jsonObj = json_decode($json);

        $campaignDetails = $client->findByPk('id', $jsonObj->id);

        $clientStores = new ClientStores();

        $clientStoresChannels = $clientStores->findBySql("SELECT  distinct(cd_display_name),cd.cd_id FROM icn_store st LEFT JOIN client_store_mapping csm ON st.st_id = csm.icn_store_st_id LEFT JOIN catalogue_detail cd ON cd.cd_id = catalogue_detail_cd_id WHERE is_active=1 AND client_id =:id",
                array(':id'=>$jsonObj->id)
                );

        if (!empty($clientStoresChannels['items'])) {
            $campaignDetails['stores'] = $clientStoresChannels['items'];
        } else {
            $campaignDetails['stores'] = array();
        }

        if(!empty($campaignDetails['expired_on']))
        {
            $campaignDetails['expired_on'] = date("d-m-Y H:i:s", strtotime($campaignDetails['expired_on']));
        }

        if (empty($campaignDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('CampaignController:getDeliveryChannel#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $campaignDetails);
            $this->successLog->LogInfo('CampaignController:getDeliveryChannel#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Delivery Channel retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }

    /*
     * get stores - delivery channels of selected client
     */
    
    public function getPreferedStores($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        $jsonObj = json_decode($json);
        $clientStores = new ClientStores();

        $clientStoresChannels = $clientStores->findBySql("SELECT st_name,st_id,cd.cd_id FROM icn_store st LEFT JOIN client_store_mapping csm ON st.st_id = csm.icn_store_st_id LEFT JOIN catalogue_detail cd ON cd.cd_id = catalogue_detail_cd_id WHERE is_active=1 AND cd.cd_id={$jsonObj->channels->cd_id} AND client_id =:id",
                    array(':id'=>$jsonObj->clients->id)
                );

        if (empty($clientStoresChannels)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('CampaignController:getPreferedStores#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $clientStoresChannels);
            $this->successLog->LogInfo('ClientController:getClients#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Prefered Stores retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }

    
    
    
    
    public function getCampaings($request) {
        $json = json_encode($request->parameters);
        $campaign = new Campaign();
        $jsonObj = json_decode($json);

        
        if(!empty($request->parameters['perPageItems']))
        {
            $limit = intval($request->parameters['perPageItems']);
            $offset = intval($request->parameters['perPageItems'] * $request->parameters['currentPage']);
            if ($offset != 0) {
                $offset = $offset - intval($request->parameters['perPageItems']);
            }
        
              $campaignDetails = $campaign->findAll([], ['limit' => $limit, 'offset' => $offset, 'order' => 'id desc']

            );

        }
        else
        {
               $campaignDetails = $campaign->findAll([
                   'is_active'=>['=',1]
               ], []);
            
        }

          
        $clientsWithStores = array();
        foreach ($campaignDetails['items'] as $clientDetail) {
            
            $client = new Clients();
            $client =  $client->findByPk('id',$clientDetail['client_id']);
            
            $clientDetail['clientDetail'] = $client;
            
            $status = "active";
            
            $d1 = new DateTime();
            $d2 = new DateTime($clientDetail['end_date']);
            
            if($d1 > $d2)
            {
                $status = "Expired";
            }
            else
            {
                $status = "Active";
            }
            
            $clientDetail['status'] = $status; 

            $clientsWithStores[] = $clientDetail;
            
        }
        $campaignDetails['items'] = $clientsWithStores;

        if (empty($campaignDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD,'clientsDetails'=>$campaignDetails);
            $this->errorLog->LogInfo('CampaignController:getCampaings#'.json_encode(array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD)));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $campaignDetails);
            $this->successLog->LogInfo('CampaignController:getCampaings#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Campaings retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }
    
    
    
    
    public function findOne($request) {
        $json = json_encode($request->parameters);
        $campaign = new Campaign();
        $jsonObj = json_decode($json);

        $campaign = $campaign->findByPk('id', $jsonObj->id);


         $campainStore = new CampaignStores();
            $campaignStores = $campainStore->findAll(
                    array(
                        'campaign_id' => array('=',$campaign['id']),
                        'is_active' => array('=',1)
                    
                        )
                );

        $campaign['start_date'] = date("d-m-Y H:i:s",  strtotime($campaign['start_date']));
        $campaign['end_date'] = date("d-m-Y H:i:s",  strtotime($campaign['end_date']));
          $stores = array();  
           if(!empty($campaignStores['totalItemsFound']))
           {
               
                foreach($campaignStores['items'] as $item)
                {
                    $stores[] = array('st_id'=>$item['icn_store_st_id']);
                }
           }
           
           $campaign['stores'] = $stores;
           
          
           
        if (empty($campaign)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $campaign);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getCampaignsByClient($request) {
        $json = json_encode($request->parameters);
        $campaign = new Campaign();
        $jsonObj = json_decode($json);


        if(!empty($jsonObj->channels->cd_id) && !empty($jsonObj->clients->id))
        {

            $campaigns = $campaign->findBySql(
                    "SELECT *   FROM campaigns WHERE catalogue_detail_cd_id=:cd_id AND "
                    . "client_id=:client_id AND "
                    . "end_date > NOW()",
                            array(
                                ":cd_id"=>$jsonObj->channels->cd_id,
                                ":client_id"=>$jsonObj->clients->id                               
                                )
                );
        

            $camps = array();
            if(!empty($campaigns['totalItemsFound']) && $campaigns['totalItemsFound'] > 0 ) 
            {
                foreach ($campaigns['items'] as $camp)
                {
                    
                    $d1=  new DateTime($camp['start_date']);
                    $d2=  new DateTime();
                    if( $d1 > $d2 )
                    {
                        $camp['campaign_start_date'] = date('d-m-Y H:i:s',strtotime($camp['start_date']));
                    }
                    else
                    {
                        $camp['campaign_start_date'] = date('d-m-Y H:i:s',time());
                     
                    }
                     $camp['campaign_end_date'] = date('d-m-Y H:i:s',strtotime($camp['end_date']));
                     
                    $camps[] = $camp;
                    //$camps[] = array('id'=>$camp['id'],'campaign_name'=>$camp['campaign_name']);
                }
            }
        
        
            if (empty($campaign)) {
                $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
                $this->errorLog->LogInfo('CampaignController:getCampaignsByClient#'.json_encode($response));
                $this->outputError($response);
                return;
            } else {
                $response = array("status" => "success", "status_code" => '200', 'camapigns' => $camps);
                $this->successLog->LogInfo('CampaignController:getCampaignsByClient#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Campaigns By Client retrieved successfully!")));
                $this->outputSuccess($response);
                return;
            }
        
        }
    }

    
    
}
?>