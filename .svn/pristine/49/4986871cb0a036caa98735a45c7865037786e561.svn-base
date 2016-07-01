<?php
use VOP\Utils\PdoUtils;

require_once APP.'Models/BaseModel.php';
require_once APP.'Utils/PdoUtils.php';

/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:17
 */
class Banner extends BaseModel {
    public $table_id;

    public $table_banner_name;
    public $table_client_id;
    public $table_catalogue_detail_cd_id;
    public $table_campaign_id;
    public $table_start_date;
    public $table_end_date;
    public $table_click_action_url;
    public $table_is_published;
    public $table_created_on;
    public $table_created_by;
    public $table_modified_by;
    public $scenario;
    //public $table_is_active; 
    public $table_modified_on;
    public $primary_column = "id";
    
    public function __construct() {
       
        $this->scenario = 'insert';
        
   }

   
   
   
    public function tableName()
    {
        return "banners";
    }
   
   
    public function validateInputParam($jsonObj) {
        $requiredProps = array('storeId');

        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
    }
    
}