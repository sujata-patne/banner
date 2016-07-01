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
class Multiselect extends BaseModel {
    public $table_cmd_id;
    public $table_cmd_group_id;
    public $table_cmd_entity_type;
    public $table_cmd_entity_detail;
    public $table_cmd_crud_isactive;

    public $scenario;
    //public $table_is_active; 
    public $primary_column = "cmd_id";
    
    public function __construct() {
       
        $this->scenario = 'insert';
        
   }
   
    public function tableName()
    {
        return "multiselect_metadata_detail";
    }
    
}