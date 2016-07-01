

<?php
 class Helper {
     
     public static function getSiteUser()
     {
         $headers =  getallheaders();

            if(!empty($headers['Accept']))
            {
                session_id($headers['Accept']);
                session_start();
            }


            if(isset($_SESSION) && !empty($_SESSION['LoggedUser']))
            {
            
                return $_SESSION;
            }
            else
            {
                return array();
            }
     
     }
     public static function getDate()
     {
        return  date("y-m-d H:i:s",time());
     }
             
     public static function getSiteUserId()
     {
         $headers =  getallheaders();

            if(!empty($headers['Accept']))
            {
                session_id($headers['Accept']);
                if(!isset($_SESSION)){session_start();}
           
            }

            if(isset($_SESSION) && !empty($_SESSION['LoggedUser']))
            {
            
                return $_SESSION['LoggedUser']['ld_user_name'];
            }
            else
            {
                return NULL;
            }
     
     }
     
     public static function getFilesMaped($Allfiles)
     {
        $mainArray = array();                
        foreach ($Allfiles as $key =>$values)
        {
            for($j = 0 ; $j<  count($Allfiles['name']); $j++)
            {
                foreach($values as $val)
                {
                    $mainArray[$j][$key] = $Allfiles[$key] [$j];
                }
            }
        }
        
        return $mainArray;
        
     }
     
 }