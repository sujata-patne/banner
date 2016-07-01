<?php
use VOP\Utils\PdoUtils;
require_once APP.'Utils/PdoUtils.php';
require_once APP."Daos/BaseDao.php";
require_once APP."Daos/UserDao.php";


class BaseModel {

    public $created_on;
    public $updated_on;
    public $created_by;
    public $updated_by;

    
    
    public function getPrimaryKey()
    {
            if(!empty($this->primary_column) && !isset($this->primary_column))
            {
                return "id";
            }
            else
            {
                return $this->primary_column;
            }
    }
    
    
    protected function hasRequiredProperties($obj, $propertiesArr) {


        if (empty($obj)) {
            return 'Invalid JSON';
        }
        foreach ($propertiesArr as $property) {
            if (!property_exists($obj, $property)) {
                return 'Invalid JSON - Property Missing: ' . $property;
            }
        }
		 
        return Message::SUCCESS;
    }

    protected function requiredPropertiesNotNullOrEmpty($propertiesArr) {

        foreach ($propertiesArr as $property) {

            if (!property_exists($this, $property)) {
                return 'Property Missing: ' . $property;
            }

            if (empty($this->$property)) {
                return 'Property Missing: ' . $property;
            }
        }

        return Message::SUCCESS;
    }

    public function setCCUUToNow($userId) {
        $now = time();

        $this->created_on = $now;
        $this->updated_on = $now;

        $this->created_by = $userId;
        $this->updated_by = $userId;
    }
    public function logCurlAPI($extractParams){
        $logFile = LOGS.'CurlModel_'.date('d-m-Y-H').'.log';
        $fs = fopen($logFile, 'a') or die("can't open file");
        fwrite($fs, "\r\n");
        file_put_contents($logFile, var_export($extractParams, true),FILE_APPEND);

    }
    public function unsetValues($propertiesArr) {
 
        foreach ($propertiesArr as $property) {
            if (property_exists($this, $property)) {
                unset($this->$property);
            }
        }
    }

    public function setValuesFromJsonObjParent($jsonObj) {

        if (is_null($jsonObj)) {
            return false;
        }
        foreach ($jsonObj as $property => $val) {
        	
            $type = gettype($val);

            if (!$this->isBasicType($type)) {
                continue;
            }

            if (property_exists($this, $property)) {
                $this->$property = $val;
            }
        }

        return true;
    }

    public function isBasicType($type) {

        switch ($type) {
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
                return true;
            default :
                return false;
        }
    }

    
    public function setAttributeValue($attribute,$value)
            
    {
        if(property_exists($this, $attribute))
        {
            if (preg_match('/table_/',$attribute))
            {
          
                $this->$attribute = strip_tags($value);
                
            }
        }        
    }

    public function getAttributeValue($attribute)

    {
        if(property_exists($this, $attribute))
        {
            if (preg_match('/table_/',$attribute))
            {

                return $this->$attribute;

            }
        }
    }
    
    
    
    public function getMaxPrimaryKey($tableName,$col){ //$storeId
        
        $dbConnection = PdoUtils::obtainConnection('CMS');
        if ($dbConnection == null) {
            return Message::ERROR_NO_DB_CONNECTION;
        }
        $dbConnection->beginTransaction();
        $storeVendorDetails = array();
        try {
            
            $baseDao = new VOP\Daos\BaseDao($dbConnection);
            
            $storeVendorDetails = $baseDao->getMaxPrimaryId($tableName, $col); //$storeId
            $dbConnection->commit();
            
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $storeVendorDetails;
        
    }

    
    public function save($userObj){
        
        $dbConnection = PdoUtils::obtainConnection('CMS');
        if ($dbConnection == null) {
            return Message::ERROR_NO_DB_CONNECTION;
        }
        $dbConnection->beginTransaction();
        $storeVendorDetails = array();
        try {
            $userDao = new VOP\Daos\BaseDao ($dbConnection);

            if($userObj->scenario == 'insert')
            {
                $storeVendorDetails = $userDao->save($userObj); //$storeId
            }
            else
            {
                $storeVendorDetails = $userDao->update($userObj); //$storeId
            }    
            $dbConnection->commit();
            
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $storeVendorDetails;
    }

    
        /*
     *  This will be return all records with given criteria 
     */
    
    public function  findAll($conditions=array(),$params= array(),$cols=array())
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
            $userDao = new \VOP\Daos\BaseDao($dbConnection);
            $offset = 0;
            
            if(isset($params['limit']))
            {
                $limit = (int)$params['limit'];
                
                if(isset($params['offset']) )
                {
                    $offset = (int)$params['offset'];
                }
                
            }
            
            $order = "";
            if(!empty($params['order']))
            {
                $order = $params['order'];
            }
            
            
            $tableName = $this->tableName(); 
            $where = "";
            if(!empty($conditions))
            {
                
                $where.="WHERE ";
                
                foreach($conditions as $column=>$value)
                {                
                    $where.=" {$column} {$value[0]} '{$value[1]}' AND";

                }
                $where= rtrim($where,'AND');
            }


            $queryForCount = "SELECT 
                        count(*) totalItems 
                      FROM 
                        {$tableName} ".$where; 


            $statementCount = $userDao->dbConnection->prepare($queryForCount);
            $result = $statementCount->execute();
            $rowCount = 0;
            $rowCount = $statementCount->fetch();
            
            $columnsInQuery = "*";
            if(!empty($cols))
            {
                $columnsInQuery = "";
                foreach ($cols as $colName)
                {
                    $columnsInQuery.=$colName.",";
                }
                
                $columnsInQuery = rtrim($columnsInQuery,',');
            }
            
            $queryWithLimit = "SELECT 
                    {$columnsInQuery} 
                  FROM 
                    {$tableName} ".$where  ;
                    
                if(!empty($params['order']))
                {
                    $queryWithLimit.=" ORDER BY ".$order;
                }

                 
                if( !empty($params['limit']) )
                {
                   $queryWithLimit.=" LIMIT 
                    {$limit} 
                  OFFSET
                    {$offset}";
                }  
   
            $statement = $userDao->dbConnection->prepare($queryWithLimit);

            $result = $statement->execute();

            $statement->setFetchMode(\PDO::FETCH_ASSOC);
                $users = array();
                while($row = $statement->fetch()) 
                {
                    $users[] = $row;
                }
		
        
               $userDetails= array('totalItemsFound'=>$rowCount['totalItems'], 'items'=> $users);      
            
             $dbConnection->commit();
            
             $userDao->dbConnection = null; 
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
            
    }
    
    
    /*
     *  This will be return all records with given criteria 
     */
    
    public function  find($conditions=array(),$params= array(),$cols=array())
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
            $userDao = new \VOP\Daos\BaseDao($dbConnection);
            $offset = 0;
            
            if(isset($params['limit']))
            {
                $limit = (int)$params['limit'];
                
                if(isset($params['offset']) )
                {
                    $offset = (int)$params['offset'];
                }
                
            }
            
            $order = "";
            if(!empty($params['order']))
            {
                $order = $params['order'];
            }
            
            
            $tableName = $this->tableName(); 
            $where = "";
            if(!empty($conditions))
            {
                
                $where.="WHERE ";
                
                foreach($conditions as $column=>$value)
                {                
                    $where.=" {$column} {$value[0]} '{$value[1]}' AND";

                }
                $where= rtrim($where,'AND');
            }


            $queryForCount = "SELECT 
                        count(*) totalItems 
                      FROM 
                        {$tableName} ".$where; 



            $statementCount = $userDao->dbConnection->prepare($queryForCount);
            $result = $statementCount->execute();
            $rowCount = 0;
            $rowCount = $statementCount->fetch();
            
            $columnsInQuery = "*";
            if(!empty($cols))
            {
                $columnsInQuery = "";
                foreach ($cols as $colName)
                {
                    $columnsInQuery.=$colName.",";
                }
                
                $columnsInQuery = rtrim($columnsInQuery,',');
            }
            
            $queryWithLimit = "SELECT 
                    {$columnsInQuery} 
                  FROM 
                    {$tableName} ".$where  ;
                    
                if(!empty($params['order']))
                {
                    $queryWithLimit.=" ORDER BY ".$order;
                }

                 
                if( !empty($params['limit']) )
                {
                   $queryWithLimit.=" LIMIT 
                    {$limit} 
                  OFFSET
                    {$offset}";
                }  

                
            $statement = $userDao->dbConnection->prepare($queryWithLimit);

            $result = $statement->execute();

            $statement->setFetchMode(\PDO::FETCH_ASSOC);
                $users = array();
                $row = $statement->fetch(); 
                
        
               $userDetails= array('totalItemsFound'=>$rowCount['totalItems'], 'item'=> $row);      
            
             $dbConnection->commit();
            
             $userDao->dbConnection = null; 
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    
        
    }
    
    
    /*
     *  This will be return all records with given criteria 
     */
    
    public function  findBySql($query="",$params= array(),$cols=array())
    {
        
        if(!empty($this->options['db']))
        {
           $dbConnection = PdoUtils::obtainConnection($this->options['db']);
        }
        else
        {
           $dbConnection = PdoUtils::obtainConnection('CMS');
        }
        $dbConnection->beginTransaction();
        
        $userDetails = array();
        try {
            $userDao = new \VOP\Daos\BaseDao($dbConnection);
                
            $statement = $userDao->dbConnection->prepare($query);
                    
            if(!empty($params))
            {
                foreach($params as $paramKey=>$paramValue)
                {
                    $statement->bindValue($paramKey,$paramValue);
                }
            }
            
            
            $result = $statement->execute();

            $statement->setFetchMode(\PDO::FETCH_ASSOC);
                $users = array();
                $rows = $statement->fetchAll(); 
                

                if(!empty($rows))
                {
                    
                    $userDetails= array('totalItemsFound'=>count($rows), 'items'=> $rows);      
                }
                else
                {
                    $userDetails= array('totalItemsFound'=>0, 'items'=> array());      
                }
        

            
             $dbConnection->commit();
            
            $userDao->dbConnection = null ; 
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    
        
    }
    
    
    

    
        /*
     *  This will be return all records with given criteria 
     */
    
    public function  findOne($conditions=array(),$params= array(),$cols=array())
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
            $userDao = new \VOP\Daos\BaseDao($dbConnection);
            
            
            $tableName = $this->tableName(); 
            $where = "";
            if(!empty($conditions))
            {
                
                $where.="WHERE ";
                
                foreach($conditions as $column=>$value)
                {                
                    $where.=" {$column} {$value[0]} '{$value[1]}' AND";

                }
                $where= rtrim($where,'AND');
            }

          $columnsInQuery = "*";
            if(!empty($cols))
            {
                $columnsInQuery = "";
                foreach ($cols as $colName)
                {
                    $columnsInQuery.=$colName.",";
                }
                
                $columnsInQuery = rtrim($columnsInQuery,',');
            }
            
            $queryWithLimit = "SELECT 
                    {$columnsInQuery} 
                  FROM 
                    {$tableName} ".$where  ;
             
                $users = array();
                try {
                    $statement = $userDao->dbConnection->prepare($queryWithLimit);
                    
                    
                    $result = $statement->execute();

                 
                    $row = $statement->fetch(); 
                } catch (Exception $ex) {
                
                    print_r($ex);
                }
                $userDetails= array('item'=> $row);      
            
             $dbConnection->commit();
            $userDao->dbConnection = null ; 
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    
        
    }
    
    
    
    
        /*
     * this will find single record of the model by its provided ID ; 
     */
    public function  findByPk($colName,$id)
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
                $userDao = new \VOP\Daos\BaseDao($dbConnection);
                $tableName = $this->tableName();
                $query = "SELECT * FROM {$tableName}  where {$colName}= {$id}";

                $statementCount = $userDao->dbConnection->prepare($query);

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

            
            $dbConnection->commit();
            
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    
        
    }
        /*
     * this will find single record of the model by its provided ID ; 
     */
    public function  updateAll($condition,$cols)
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {
                $userDao = new \VOP\Daos\BaseDao($dbConnection);
                $tableName = $this->tableName();
                $query = "UPDATE   {$tableName} SET ";
                $set = "";
                
                foreach($cols as $key=>$val)
                {
                    $set.="{$key} = '{$val}',"; 
                }
                $set = rtrim($set,',');
                $where =" WHERE ";
                foreach($condition as $column=>$value)
                {                
                    $where.=" {$column} {$value[0]} '{$value[1]}' AND";

                }
                $where= rtrim($where,'AND');
                $query= $query.$set.$where; 
                $statementCount = $userDao->dbConnection->prepare($query);
                $result = $statementCount->execute();
                $dbConnection->commit();
                if($result)
                {
                    return $result; 
                }
                else {
                   return 0; 
                }

            
          
            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    }
    
    
    /*
     * this will find single record of the model by its provided ID ; 
     */
    public function  updateByPk($id,$updateCols)
    {
       $dbConnection = PdoUtils::obtainConnection('CMS');
     
        $dbConnection->beginTransaction();
        $userDetails = array();
        try {            
            $userDao = new UserDao($dbConnection);
           // $userDetails = $userDao->updateOneColumn($col,$val,$updateCols);
            $tableName = $this->tableName();
            $primaryColumnName = $this->getPrimaryKey();
            
            $set = "";
            
            foreach($updateCols as $key=>$val)
            {
                $set.= $key."='".$val."',";
            }
            
            $set = rtrim($set,',');
            
             $query = "UPDATE {$tableName} set {$set}  where {$primaryColumnName}={$id}";

              $statementCount = $userDao->dbConnection->prepare($query);

             $result = $statementCount->execute();
            
            $dbConnection->commit();
            if($result)
              {
                  return 1; 
              }
              else {
                 return 0; 
              }
            

            
        } catch (\Exception $e) {
            $dbConnection->rollBack();
            echo $e->getMessage();
            exit;
        }
        PdoUtils::closeConnection($dbConnection);
        return $userDetails;
    }
 
    
    
}
