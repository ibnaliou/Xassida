<?php
    define('DB_HOST',$_SERVER['HTTP_HOST']);
    define('DB_USER','root');
    define('DB_PASSWORD','');
    define('DB_NAME','xassida_old');
    $con=null;
    try {
        $con= new PDO ('mysql: host='.DB_HOST.';dbname ='.DB_NAME,DB_USER,DB_PASSWORD);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);	
        //echo "CONNEXION OK +++++++++++++++++++++++";
    } catch (PDOException $e) {
        echo "--------------- KO CONNEXION ";
    }		
   
    function getPrepIns($table){
        $prep['xassida']="INSERT INTO `".DB_NAME."`.`xassida` (`id`, `nom`, `description`) VALUES (NULL, ?, '');";
        $prep['bayite']="INSERT INTO `".DB_NAME."`.`bayite` (`id`, `xassida_id`, `bayite`) VALUES (NULL,?,?);";
        $prep['trans']="INSERT INTO `".DB_NAME."`.`trans` (`id`, `bayite_id`, `langue_id`,`trans`) VALUES (NULL, ?, ?, ?);";
       // echo ",,,,,,,,,,, PREPAREQ OK ,,,,,,,,,,,,";
        return $prep[$table];	
    }
   
    function setReq($con,$prepa,$data){
        try {
            $stmt = $con->prepare($prepa);
            try {
                $a=array_values($data);
                $stmt->execute($a);
                return $con->lastInsertId();			
            } catch(PDOException $e) {
                print "Error!: " . $e->getMessage() . "</br>";
                return 0;
            }
        } catch(PDOException $e) {
            print "Error!: " . $e->getMessage() . "</br>";
            return 0;
        }	
    } 

?>