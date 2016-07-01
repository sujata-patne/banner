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
class Clients extends BaseModel {
    public $table_id;
    public $table_icn_login_detail_id;
    public $table_client_name;
    public $table_description;
    public $table_expired_on;
    public $table_contact_name;
    public $table_contact_no;
    public $table_email;
    public $table_created_on;
    public $table_created_by;
    public $scenario;
    public $table_is_active; 
    public $table_modified_on;
    public $table_modified_by;
    public $primary_column = "id";
    public function __construct() {
       
         $this->scenario = 'insert';
        $this->table_icn_login_detail_id = 0;
        $this->table_is_active = 1; 
   }

   
    public function tableName()
    {
        return "clients";
    }
   
   
    public function validateInputParam($jsonObj) {
        $requiredProps = array('storeId');

        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
    }

    
    
    
    
}