<?php
require_once APP.'Models/Message.php';
require_once APP.'Models/Store.php';
/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:12
 */
class StoreController extends BaseController {

    public function __construct(){
        parent::__construct();
    }
    public function getAction($request){
        parent::display($request);
    }
   /* public function postAction($request){
        parent::display($request);
    }*/

    public function getStoreDetailsByStoreId($request){
        $json = json_encode( $request->parameters );
        $store = new Store();
        $jsonObj = json_decode($json);

        $validationMessage = $store->validateInputParam($jsonObj);
        if ($validationMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $validationMessage);
            $this->outputError($response);
            return;
        }
        if (!$store->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }
        if (trim( $jsonObj->storeId == '' )) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_STORE_ID );
            $this->outputError($response);
            return;
        }
        $storeDetails = $store->getStoreDetailsByStoreId( $jsonObj->storeId );
        $this->logCurlAPI($storeDetails);

        if( empty( $storeDetails ) ){
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD );
            $this->outputError($response);
            return;
        }else{
            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'storeDetails' => $storeDetails );
            $this->outputSuccess($response);
            return;
        }

    }
    
    
    
    public function getStores($request){
        $json = json_encode( $request->parameters );
        $store = new Store();
        $jsonObj = json_decode($json);

        if (!$store->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }

        $storeDetails = $store->getStores();
        $this->logCurlAPI($storeDetails);

        if( empty( $storeDetails ) ){
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD );
            $this->outputError($response);
            return;
        }else{
            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'storeDetails' => $storeDetails );
            $this->outputSuccess($response);
            return;
        }

    }
    
    
    
    
    public function getStoresDeliveries($request){
        
            $json = json_encode( $request->parameters );
            $store = new Store();
            $jsonObj = json_decode($json);

            if (!$store->setValuesFromJsonObj($jsonObj)) {
                $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
                $this->outputError($response);
                return;
            }
        
            
            $userId = 0; 
            
            if(!empty($request->parameters['access_token']))
            {   
                session_id($request->parameters['access_token']); 
                session_start();
                $userId = $_SESSION['LoggedUser']['ld_id'];
            }
            
        
        
        $storeList = $store->findBySql("SELECT CONCAT(st_name,' - ',cd_display_name) as store_delivery_channel,st_id,cd.cd_id FROM icn_store st, multiselect_metadata_detail multi, catalogue_detail cd WHERE st.st_front_type = multi.cmd_group_id
AND multi.cmd_entity_detail = cd.cd_id AND st.st_id IN(SELECT su_st_id FROM icn_store_user)");

        //WHERE su_ld_id = {$userId})
        
        if( empty( $storeList ) ){
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD );
            $this->errorLog->LogInfo('StoreController:getStoresDeliveries#'.json_encode($response));
            $this->outputError($response);
            return;
        }else{
            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'storeDetails' => $storeList );
            $this->successLog->LogInfo('StoreController:getStoresDeliveries#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Storelist retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }

    }
    
    
    
    public function getVendorsList($request){
        $json = json_encode( $request->parameters );
        $store = new Store();
        $jsonObj = json_decode($json);

        //$validationMessage = $store->validateInputParam($jsonObj);
        /*if ($validationMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $validationMessage);
            $this->outputError($response);
            return;
        }*/
        if (!$store->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }
        /*if (trim( $jsonObj->storeId == '' )) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_STORE_ID );
            $this->outputError($response);
            return;
        }*/
        $storeVendorDetails = $store->getVendorsList();    //$jsonObj->storeId
         if( empty( $storeVendorDetails ) ){
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD );
            $this->outputError($response);
            return;
        }else{
            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'storeVendorDetails' => $storeVendorDetails );
            $this->outputSuccess($response);
            return;
        }

    }

   


}