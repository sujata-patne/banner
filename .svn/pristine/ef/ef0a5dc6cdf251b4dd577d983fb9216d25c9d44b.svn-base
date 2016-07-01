<?php
use VOP\Utils\PdoUtils;

require_once APP."Daos/StoreDao.php";
require_once APP.'Models/BaseModel.php';
require_once APP.'Utils/PdoUtils.php';

/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:17
 */
class CampaignStores extends BaseModel {
    
    public $table_id;
    public $table_campaign_id;
    public $table_icn_store_st_id;
    
    public $table_is_active;
    public $table_created_on;
    public $table_created_by;
    public $table_modified_on;
    public $table_modified_by;
    public $primary_column  = "id";
    public $scenario;
   
    public function __construct() {
       

        $this->scenario = 'insert';
        $this->table_is_active = 1; 
        $this->primary_column  = "id";
   }

   
    public function tableName()
    {
        return "campaign_store_mapping";
    }
   
   
    public function validateInputParam($jsonObj) {
        $requiredProps = array('id');

        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
    }

}