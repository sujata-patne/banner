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
class BannerMultiselect extends BaseModel {
    public $table_id;

    public $table_banner_id;
    public $table_relation_type;
    public $table_relation_id;
    
    public $table_created_on;
    public $table_created_by;
    public $table_modified_by;
    public $table_modified_on;
    public $table_crud_isactive;
    
    public $scenario;
    public $primary_column = "id";
    
    public function __construct() {
       
        $this->scenario = 'insert';
   }

   
   
   
    public function tableName()
    {
        return "banner_multiselect";
    }
   

    
}