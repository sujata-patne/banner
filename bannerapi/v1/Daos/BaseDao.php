<?php

namespace VOP\Daos;

class BaseDao {

    public $dbConnection;

    public function __construct($dbConn) {
        $this->dbConnection = $dbConn;
    }
    public function logCurlAPI($extractParams){
        $logFile = LOGS.'CurlDaos_'.date('d-m-Y-H').'.log';
        $fs = fopen($logFile, 'a') or die("can't open file");
        fwrite($fs, "\r\n");
        file_put_contents($logFile, var_export($extractParams, true),FILE_APPEND);

    }
    protected function ccuuFromRow($obj, $row) {
        $obj->created_at = $row["created_on"];
        $obj->created_by = $row["created_by"];
        $obj->updated_at = $row["updated_on"];
        $obj->updated_by = $row["updated_by"];
    }

    public function bindCCUU(&$statement, $model) {
        $now = time();
        $statement->bindParam(':created_on', $now);
        $statement->bindParam(':created_by', $model->created_by);
        $statement->bindParam(':updated_on', $now);
        $statement->bindParam(':updated_by', $model->updated_by);
    }

 /*
  * params model Object 
  * ===================FLOW===============
  * 
  * this function will accept $model as model's object 
  *#1 forloop =  then it will build the query for db using attributes of the $model object
  * #2 forloop then bind values to it 
  * 
  *
  */   
    
public function save($model)
{
           
        $tableName = $model->tableName();
                
        
        if($model->scenario == 'update')
        {
            $query = "UPDATE $tableName ";
          
        }
        else{
            $query = "INSERT INTO $tableName ";

        }
        
        $cols = " (";
        $vals = " VALUES(";
        // #1 forloop
        foreach ($model as $key=>$val)
        {
         if (preg_match('/table_/',$key))
            {     
                $key= str_replace('table_','',$key);
                $cols.="$key,";
                $vals.=":$key,";
            }
        }
        $cols = rtrim($cols,',');
        $cols.=") ";
        
        $vals = rtrim($vals,',');
        
        $vals.=") ";
        
        $query.= $cols.$vals;
  
        $statement = $this->dbConnection->prepare($query);
        //#1 forloop
        /*
         *  this foreach will assign values with bind value function of PDO 
         */
        
        foreach ($model as $key=>$val)
        {
           if (preg_match('/table_/',$key))
            {
                $key= str_replace('table_','',$key);
                
                $statement->bindValue(":$key", $val);
            }
        }
        
        $result = $statement->execute();
      
        if($result)
        {
            return $model;
        }
        else {
            return 0; 
        }
   }
    

   
    public function update($model)
    {

        $tableName = $model->tableName();
        $id = $model->id; 
     
        $query = "UPDATE $tableName  SET ";
        
        $cols = '';

        // #1 forloop
        foreach ($model as $key=>$val)
        {
         if (preg_match('/table_/',$key))
            {     
                $key= str_replace('table_','',$key);
                $val = $val;
                $cols.="$key=:$key,";

            }
        }
        $cols = rtrim($cols,',');
        $query.= $cols;
        $primaryKey = $model->getPrimaryKey();
        $query.=" WHERE {$primaryKey} = $id"; 
        
        
        $statement = $this->dbConnection->prepare($query);
        
        foreach ($model as $key=>$val)
        {
           if (preg_match('/table_/',$key))
            {
                $key= str_replace('table_','',$key);
                
                $statement->bindValue(":$key", $val);
            }
        }

        $result = $statement->execute();
       
        if($result)
        {
           return $model;
           
        }  else {
            return 0 ; 
        }
   }
    

   
   /*
    *   @Params - table name for which we are trying to get its last primary key
    *   $param - col  we can check other cols or tables col by its  second param  col name 
    * 
    *  ========== FLOW ===================
    *  query last primary key of the table
    *  if found give it  otherwise 
    *  give  1
    */
   
    public function getMaxPrimaryId($tableName,$col)
    {
        $query =  "SELECT MAX($col) las_primary_key FROM $tableName";
        $statement = $this->dbConnection->prepare($query);
        $result = $statement->execute();
        $res = $statement->fetch();
        if(isset($res['las_primary_key']) && $res['las_primary_key'] > 0)
        {
            return $res['las_primary_key'];
        }  
        else {            
            return 0;            
       }
    }
    
    
    
   function updateOneColumn($priamary_col,$primary_val,$colval= array()) {
    
       $colName  = key($colval);
       $colval = $colval[$colName];

       $query = "UPDATE icn_login_detail set {$colName}='{$colval}'  where {$priamary_col}={$primary_val}";
        
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
    
   
   
    public function findAll($conditions=array(),$params = array()) {
        $limit = intval( $params['perPageItems'] );
        $offset = intval( $params['perPageItems'] * $params['currentPage'] );
        if($offset != 0)
        {
            $offset = $offset-intval( $params['perPageItems'] );
        }
        
        $where = "";
        $where.="WHERE ";
        $where.=" ld_user_type='{$params['userType']}'";
        $where.="AND ld_crud_isactive='{$params['ld_crud_isactive']}'";
        
        $queryForCount = "SELECT 
                    count(*) totalItems 
                  FROM 
                    icn_login_detail ".$where; 

        
        $statementCount = $this->dbConnection->prepare($queryForCount);
 
        $result = $statementCount->execute();
        
        $statementCount->setFetchMode(\PDO::FETCH_ASSOC);
        $rowCount = 0;
        $rowCount = $statementCount->fetch();
                    
        
        $queryWithLimit = "SELECT 
                    * 
                  FROM 
                    icn_login_detail ".$where ." 
                  LIMIT 
                    {$limit} 
                  OFFSET
                    {$offset}";
        
        $statement = $this->dbConnection->prepare($queryWithLimit);
 
        $result = $statement->execute();
        
        
        
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
		
        $users = array();
		while($row = $statement->fetch()) {
                    $users[] = $row;
            }
		
        
        return array('totalItemsFound'=>$rowCount['totalItems'], 'items'=> $users);       
   }


}
