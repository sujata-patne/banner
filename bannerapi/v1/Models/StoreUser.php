<?php
use VOP\Utils\PdoUtils;

require_once APP."Daos/StoreUserDao.php";
require_once APP.'Models/BaseModel.php';
require_once APP.'Utils/PdoUtils.php';
require_once APP."Daos/BaseDao.php";


/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 12:17
 */
class StoreUser extends BaseModel {
    public $table_su_st_id;
    public $table_su_ld_id;
    public $table_su_created_on;
    public $table_su_modified_on;
    public $table_su_created_by;
    public $table_su_modified_by;

    public $table_su_crud_isactive;
 
    public function __construct() {
        $this->table_su_crud_isactive = 1;
        
    }

    public function tableName()
    {
        return "icn_store_user";
    }

    
    
    
    public function mapStoreToUser($user_id,$st_id)
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
            $storeDao = new StoreUserDao($dbConnection);
            
            $userDetails = $storeDao->mapStoreToUser($user_id,$id);
            
            $dbConnection->commit();
            
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    
        
        
    }
    
    
    
    public function removeAllStoresOfUser($userId)
    {
        $dbConnection = PdoUtils::obtainConnection('CMS');
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
            $userDao = new \VOP\Daos\BaseDao($dbConnection);
            $query = "UPDATE icn_store_user set su_crud_isactive=0 where su_ld_id= {$userId}";
            $statement = $userDao->dbConnection->prepare($query);
            $result = $statement->execute();
           return $result;
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