<?php

use VOP\Utils\PdoUtils;

require_once(APP."Daos/CampaignDao.php");
require_once(APP."Models/BaseModel.php");
require_once(APP."Models/Message.php");
require_once(APP."Utils/PdoUtils.php");
require_once(APP."Utils/Helper.php");

class Campaign extends BaseModel {

    public $table_id;
    public $table_catalogue_detail_cd_id;
    public $table_client_id;
    public $table_campaign_name;
    public $table_description;
    public $table_start_date;
    public $table_end_date;
    public $table_total_impression;
    public $table_total_clicks;
    public $table_priority;
    public $table_instruction;
    public $table_created_on;
    public $table_created_by;
    public $table_modified_by;
    public $table_modified_on;
    public $scenario;
    public $id; 
    public $primary_column ='id' ; 

        
        public function tableName()
        {
            return "campaigns";
        }
        
        
	public function __construct($json = NULL) {
            
        if (is_null($json)) {
                return;
        }
        

        $this->primary_column ="id"; 
        $this->scenario = "insert";
//        $this->table_is_active = 1; 
 
//		$this->setValuesFromJsonObj($json);
	}
	
	public function validateJson($jsonObj) {
		$requiredProps = array('promoId' );
	
		$message = $this->hasRequiredProperties($jsonObj, $requiredProps);
		return $message;
	}
	
	public function validateJsonObj($jsonObj) {
		$requiredProps = array('promoId', 'storeId' );
	
		$message = $this->hasRequiredProperties($jsonObj, $requiredProps);
		return $message;
	}

	public function setValuesFromJsonObj($jsonObj) {
		$result = $this->setValuesFromJsonObjParent($jsonObj);

		if (!$result) {
			return $result;
		}

		return true;
	}
}
?>