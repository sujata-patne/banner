<?php

require_once APP . 'Models/Message.php';
require_once APP . 'Models/Clients.php';
require_once APP . 'Models/ClientStores.php';

/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:12
 */
class ClientController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function getAction($request) {
        parent::display($request);
    }

    /* public function postAction($request){
      parent::display($request);
      } */

    public function getStoreDetailsByStoreId($request) {
        $json = json_encode($request->parameters);
        $client = new Store();
        $jsonObj = json_decode($json);

        $validationMessage = $client->validateInputParam($jsonObj);
        if ($validationMessage != Message::SUCCESS) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => $validationMessage);
            $this->outputError($response);
            return;
        }
        if (!$client->setValuesFromJsonObj($jsonObj)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_INVAID_REQUEST_BODY);
            $this->outputError($response);
            return;
        }
        if (trim($jsonObj->storeId == '')) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_BLANK_STORE_ID);
            $this->outputError($response);
            return;
        }
        $clientDetails = $client->getStoreDetailsByStoreId($jsonObj->storeId);
        $this->logCurlAPI($clientDetails);

        if (empty($clientDetails)) {
            $response = array("status" => "ERROR", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "SUCCESS-BUSINESS", "status_code" => '200', 'storeDetails' => $clientDetails);
            $this->outputSuccess($response);
            return;
        }
    }

    public function getClients($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        $jsonObj = json_decode($json);

        if(!empty($request->parameters['perPageItems']))
        {
            $limit = intval($request->parameters['perPageItems']);
            $offset = intval($request->parameters['perPageItems'] * $request->parameters['currentPage']);
            if ($offset != 0) {
                $offset = $offset - intval($request->parameters['perPageItems']);
            }
            
              $clientDetails = $client->findAll([], ['limit' => $limit, 'offset' => $offset, 'order' => 'id desc']
                );

        }
        else
        {
               $clientDetails = $client->findBySql("SELECT * FROM clients WHERE  is_active = '1' AND expired_on > NOW()");
            
        }

          
        $clientsWithStores = array();
        foreach ($clientDetails['items'] as $clientDetail) {
    
            $clientStores = new ClientStores();
            $clientStoresChannels = $clientStores->findBySql("SELECT CONCAT(st_name,' - ',cd_display_name) as store_delivery_channel,st_id,cd.cd_id FROM icn_store st LEFT JOIN client_store_mapping csm ON st.st_id = csm.icn_store_st_id LEFT JOIN catalogue_detail cd ON cd.cd_id = catalogue_detail_cd_id WHERE is_active=1 AND client_id = :client_id",
                        array(":client_id"=>$clientDetail['id'])
                    );
            $clientDetail['stores'] = $clientStoresChannels['items'];

            $d1 = new DateTime($clientDetail['expired_on']);
            $d2 = new DateTime();
            if ($d1 < $d2) {
                $clientDetail['validity_status'] = "Expired";
            } else {
                $clientDetail['validity_status'] = "Active";
            }

            $clientsWithStores[] = $clientDetail;
        }

        $clientDetails['items'] = $clientsWithStores;

        if (empty($clientDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->errorLog->LogInfo('ClientController:getClients#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $clientDetails);
            $this->successLog->LogInfo('ClientController:getClients#'.json_encode( array("status" => "success", "status_code" => '200', 'msg' =>"Clients retrieved successfully!")));
            $this->outputSuccess($response);
            return;
        }
    }

    public function findOne($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        $jsonObj = json_decode($json);

        $clientDetails = $client->findByPk('id', $jsonObj->id);

        $clientStores = new ClientStores();

        $clientStoresChannels = $clientStores->findBySql("SELECT CONCAT(st_name,' - ',cd_display_name) as store_delivery_channel,st_id,cd.cd_id FROM icn_store st LEFT JOIN client_store_mapping csm ON st.st_id = csm.icn_store_st_id LEFT JOIN catalogue_detail cd ON cd.cd_id = catalogue_detail_cd_id WHERE is_active=1 AND client_id = :client_id",
                    array(":client_id"=>$jsonObj->id)
                );

        if (!empty($clientStoresChannels['items'])) {
            $clientDetails['stores'] = $clientStoresChannels['items'];
        } else {
            $clientDetails['stores'] = array();
        }

        if(!empty($clientDetails['expired_on']))
        {
            $clientDetails['expired_on'] = date("d-m-Y H:i:s", strtotime($clientDetails['expired_on']));
        }

        if (empty($clientDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $clientDetails);
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

        $clientDetails = $client->findByPk('id', $jsonObj->id);

        $clientStores = new ClientStores();

        $clientStoresChannels = $clientStores->findBySql("SELECT st_name,cd_display_name, CONCAT(st_name,' - ',cd_display_name) as store_delivery_channel,st_id,cd.cd_id FROM icn_store st LEFT JOIN client_store_mapping csm ON st.st_id = csm.icn_store_st_id LEFT JOIN catalogue_detail cd ON cd.cd_id = catalogue_detail_cd_id WHERE is_active=1 AND client_id = :client_id",
                    array(":client_id"=>$jsonObj->id)
                );

        if (!empty($clientStoresChannels['items'])) {
            $clientDetails['stores'] = $clientStoresChannels['items'];
        } else {
            $clientDetails['stores'] = array();
        }

        if(!empty($clientDetails['expired_on']))
        {
            $clientDetails['expired_on'] = date("d-m-Y H:i:s", strtotime($clientDetails['expired_on']));
        }

        if (empty($clientDetails)) {
            $response = array("status" => "error", "status_code" => '400', 'msgs' => Message::ERROR_STORE_LOAD);
            $this->outputError($response);
            return;
        } else {
            $response = array("status" => "success", "status_code" => '200', 'clientsDetails' => $clientDetails);
            $this->outputSuccess($response);
            return;
        }
    }

    public function create($request) {

        $json = json_encode($request->parameters);
        $client = new Store();
        $jsonObj = json_decode($json);
        $client = new Clients();
        if (isset($request->parameters['id']) && $request->parameters['scenario'] == 'update') {
            $client->scenario = "update";
            $client->setAttributeValue('table_id', $request->parameters['id']);
            $client->id = $request->parameters['id'];

            $msg = "Client updated successfully.";
        } else {
            $client->scenario == 'insert';
            $id = $client->getMaxPrimaryKey('clients', 'id');
            $id++;
            $client->setAttributeValue('table_id', $id);
            $client->id = $id;
            $msg = "Client created successfully.";
        }

        $client->setAttributeValue('table_is_active', 1);
        $client->setAttributeValue('table_client_name', preg_replace('/\s+/', ' ',$jsonObj->client_name));
        $client->setAttributeValue('table_email', $jsonObj->email);
        $client->setAttributeValue('table_contact_name', $jsonObj->contact_name);
        
        $client->setAttributeValue('table_contact_no', $jsonObj->contact_no);
        
        if(!empty($jsonObj->description))
        {
            $client->setAttributeValue('table_description', $jsonObj->description);
        }
        $client->setAttributeValue('table_expired_on', date('y-m-d H:i:ss', strtotime($jsonObj->expired_on)));
        
        
            
        if($client->scenario == 'insert')
        {
            $client->setAttributeValue('table_created_by',Helper::getSiteUserId()); 
            $client->setAttributeValue('table_created_on',Helper::getDate()); 
        }
        else 
        {
            $client->setAttributeValue('table_created_by', $request->parameters['created_by']);
            $client->setAttributeValue('table_created_on',date("y-m-d H:i:s", strtotime($request->parameters['created_on']) ));
            
            $client->setAttributeValue('table_modified_by',Helper::getSiteUserId()); 
            $client->setAttributeValue('table_modified_on',Helper::getDate());                
        }
    
        
        $clientSaved = $client->save($client);

        /*
         * 
         *  To store clients  with its store 
         */

        if (empty($clientSaved)) {

            $response = array("status" => "error", "status_code" => '400', 'msgs' => "Stores not found");
            $this->errorLog->LogInfo('ClientController:create#'.json_encode($response));
            $this->outputError($response);
            return;
        } else {

            if (!empty($jsonObj->stores)) {
                $clientStoreUpdate = new ClientStores();

                $clientStoreUpdate->updateAll(array('client_id' => array(
                        '=', $clientSaved->id,
                    )), array('is_active' => 0));

                foreach ($jsonObj->stores as $client) {

                    $clientStore = new ClientStores();
                    $clientStore->table_is_active = 1;
                    $clientStore->table_client_id = $clientSaved->id;
                    $clientStore->table_icn_store_st_id = $client->st_id;
                    $clientStore->table_catalogue_detail_cd_id = $client->cd_id;
                    $newid = $clientStore->getMaxPrimaryKey('client_store_mapping', 'id');
                    $newid++;
                    $clientStore->table_id = $newid;
                    
                          
                    if($clientStore->scenario == 'insert')
                    {
                        $clientStore->setAttributeValue('table_created_by',Helper::getSiteUserId()); 
                        $clientStore->setAttributeValue('table_created_on',Helper::getDate()); 
                    }
                    else {

                        $clientStore->setAttributeValue('table_modified_by',Helper::getSiteUserId()); 
                        $clientStore->setAttributeValue('table_modified_on',Helper::getDate());                
                    }

                    
                    
                    $clientStoreMap = $clientStore->save($clientStore);
                }
            }



            $response = array("status" => "success", "status_code" => '200', 'msg' => $msg, 'storeDetails' => $client);
            $this->successLog->LogInfo('ClientController:create#'.json_encode(array("status" => "success", "status_code" => '200', 'msg' => $msg)));
            $this->outputSuccess($response);
            return;
        }
    }

    public function block($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        if (isset($request->parameters['ld_id'])) {
            $result = $client->updateByPk($request->parameters['ld_id'], array('is_active' => '0'));

            if ($result) {
                $response = array("status" => "success", "status_code" => '200', 'msg' => "Client blocked Successfully");
                $this->outputSuccess($response);
            } else {
                $response = array("status" => "error", "status_code" => '400', 'msg' => "Unable to block this client");
                $this->outputError($response);
            }
        }
    }

    public function activate($request) {
        $json = json_encode($request->parameters);
        $client = new Clients();
        if (isset($request->parameters['ld_id'])) {
            $result = $client->updateByPk( $request->parameters['ld_id'], array('is_active' => '1'));

            if ($result) {
                $response = array("status" => "success", "status_code" => '200', 'msg' => "Client activated Successfully");
                $this->outputSuccess($response);
            } else {
                $response = array("status" => "error", "status_code" => '400', 'msg' => "Unable to activate this client");
                $this->outputError($response);
            }
        }
    }

}
