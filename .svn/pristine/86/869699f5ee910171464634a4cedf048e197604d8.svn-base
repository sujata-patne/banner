<?php
/**
 * Created by PhpStorm.
 * User: sujata.patne
 * Date: 18-01-2016
 * Time: 13:23
 */

use VOP\Daos\BaseDao;
require_once(APP."Models/User.php");
require_once(APP."Daos/BaseDao.php");

class UserDao extends BaseDao {
    public function __construct($dbConn) {
        parent::__construct($dbConn);
    }
    
    
   

   function findByPk($id) {
       
       $query = "SELECT * FROM icn_login_detail where ld_id=$id";
       
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
   
   
   

}