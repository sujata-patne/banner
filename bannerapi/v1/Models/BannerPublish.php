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
class BannerPublish extends BaseModel {
    public $table_id;
    public $table_banner_id;
    public $table_icn_pub_page_pp_id;
    public $table_icn_pub_page_portlet_ppp_id;
    public $table_start_date;
    public $table_end_date;
    public $table_timeslot_multiselect_group_id;
    public $table_user_type;
    public $table_is_active;
    public $table_operator_multiselect_group_id;
    public $table_circle_multiselect_group_id;
    public $table_handset_brand_multiselect_group_id;
    public $table_os_multiselect_group_id;
    public $table_content_handset_group_id;
    
    public $table_created_on;
    public $table_created_by;
    public $table_modified_by;
    public $scenario;
    public $table_modified_on;
    public $primary_column = "id";
    
    public function __construct() {
       
        $this->scenario = 'insert';
        
   }

   
    public function tableName()
    {
        return "banner_publish";
    }
   
   
    public function validateInputParam($jsonObj) {
        $requiredProps = array('storeId');

        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
    }
    
}