<?php
/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 13:23
 */

use VOP\Daos\BaseDao;
require_once(APP."Models/Store.php");
require_once(APP."Daos/BaseDao.php");

class StoreUserDao extends BaseDao {
    public function __construct($dbConn) {
        parent::__construct($dbConn);
    }
    

    
public  function mapStoreToUser($user_id,$store_id) {
       
       $query = "INSERT INTO icn_store_user(";
       
        $statementCount = $this->dbConnection->prepare($query);
 
        $result = $statementCount->execute();
        
        $statementCount->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statementCount->fetch();
        if($result)
        {
            return $row; 
        }
        else {
           return 0; 
        }
   }
   
   
public  function removeAllStoresOfUser($user_id) {
       
       $query = "UPDATE icn_store_user set su_crud_isactive=0 where su_ld_id= ".$user_id;
       
        $statementCount = $this->dbConnection->prepare($query);
 
        $result = $statementCount->execute();
        
        if($result)
        {
            return 1; 
        }
        else {
           return 0; 
        }
   }

   
   
   
}