<?php

use VOP\Utils\PdoUtils;

require_once APP."Daos/UserDao.php";
require_once APP.'Models/BaseModel.php';
require_once APP.'Utils/PdoUtils.php';

/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:17
 */
class User extends BaseModel {
    public $table_ld_id;
    public $table_ld_active;
    public $table_ld_user_id;
    public $table_ld_user_name;
    public $table_account_validity;
    public $table_ld_display_name;
    public $table_ld_user_pwd;
    public $table_ld_mobile_no;
    public $table_ld_email_id;
    public $table_ld_user_type;
    public $table_ld_role;
    public $table_ld_created_on;
    public $table_ld_created_by;
    public $table_ld_modified_on;
    public $table_ld_modified_by;
    public $primary_column ; 

    public function __construct() {
        
        $this->primary_column  = "ld_id";
        $this->ld_active = 1;
//        $this->ld_crud_isactive = 0;
        
    }

    
    public function rules()
    {
            return array(
                    // username and password are required
                    array('username, password', 'required'),
                    // rememberMe needs to be a boolean
                    array('rememberMe', 'boolean'),
                    // password needs to be authenticated
                    array('password', 'authenticate'),
            );
    }
    
    
    /*
     *  this will be setter function which is for setting table name for current class and its object
     */
    public function tableName()
    {
        return "icn_login_detail";
    }
    

    
    
    public function validateInputParam($jsonObj) {

        $requiredProps = array('storeId');
        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
  
    }
    
    public function validateInputParamForCG($jsonObj) {
        $requiredProps = array('storeId','deviceSize');

        $message = $this->hasRequiredProperties($jsonObj, $requiredProps);
        return $message;
    }
    public function validateInputJsonObj($jsonObj) {
        $requiredProps = array('storeId','operatorId');

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


        

    
    

    
  
    /*
     * this will find single record of the model by its provided ID ; 
     */
    public function  updateOneColumn($col,$val,$updateCols)
    {

       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {            
            $userDao = new UserDao($dbConnection);
            $userDetails = $userDao->updateOneColumn($col,$val,$updateCols);
            $dbConnection->commit();
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    }
    
  

}