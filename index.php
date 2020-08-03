<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xassida</title>
</head>
<body>
    

<?php
set_time_limit(0);
include_once "./db/config-connect.php";
$dir    = './docs';
$docs = scandir($dir);
$name_khassida=$tab=[];
for ($i=3; $i < count($docs); $i++) { 
    $etat=-1;
    $d=$docs[$i];
    $new_doc= $dir."/".$d;    
    $docs_in = scandir($new_doc);
    $keywords = preg_split("/_[0-9]+/",$d);      
    if(!array_key_exists($keywords[0],$name_khassida)){
        $name_khassida[$keywords[0]]=1;    
        $tab[$keywords[0]][$d]=1;
        $etat=1;        
    }else{
        $name_khassida[$keywords[0]]++; 
        $tab[$keywords[0]][$d]=0;
        $etat=0;
    }
    $t=[];
    for ($j=2; $j < count($docs_in); $j++) { 
        $trans_doc= $new_doc."/".$docs_in[$j];
        $docs_file = scandir($trans_doc);
        $file = $trans_doc."/".$docs_file[2];
        $t[$docs_in[$j]]= file($file);
    }
    $tab[$keywords[0]][$d]=$t;
}

echo "<pre>";
$dt_x=[];
$lang=Array("French"=>2,"English"=>3,"Italian"=>4);
foreach ($tab as $xassida => $values) { 
    $prepXass=getPrepIns("xassida");  
    $dt_x['nom']=str_replace("_", " ", $xassida);
    $id_x=setReq($con,$prepXass,$dt_x); 
    $dt_b=array('id_xassida'=> $id_x); 
    echo "==================================================================================================<br /> ";
    echo "====================================== ".$xassida." (_".count($values)."_) ==========================================<br />";
    echo "==================================================================================================<br /> ";
     foreach ($values as $name => $vlang) {  
        echo "--------------------------------------------------------------------------------------------------<br /> ";
        echo "-------------------------------------- ".$name." ------------------------------------------<br />";
        echo "--------------------------------------------------------------------------------------------------<br /> ";   
        $ar=$vlang['Arabic'];
        $tr=$vlang['Trans'];
        for ($k=0; $k < count($ar); $k++) { 
            $prepByite=getPrepIns("bayite"); 
            $dt_b['bayite']=trim($ar[$k]); 
            $id_b=setReq($con,$prepByite,$dt_b); 
            $prepTrans=getPrepIns("trans");
            $dt_t=array('id_bayite'=> $id_b);
            $dt_t['id_langue']=1;
            $sp_tr = preg_split("/^[0-9][0-9]?\./",trim($tr[$k])); 
            if(isset($sp_tr[1])){
                $tr_v=ucfirst(trim($sp_tr[1]));
            }else{
                $tr_v=ucfirst(trim($sp_tr[0]));
            }
            $dt_t['trans']=$tr_v;
            $id_t=setReq($con,$prepTrans,$dt_t); 
            echo "AR : ".trim($ar[$k])." TR : ".$tr_v;
            foreach ($lang as $val_lang => $id_langue) {
                if(isset($vlang[$val_lang])){
                    $dt_t['id_langue']=$id_langue;
                    $dt_t['trans']=utf8_encode(trim($vlang[$val_lang][$k]));
                    $id_t=setReq($con,$prepTrans,$dt_t); 
                }
            }
            echo "</br>";
        } 
    }
}
?>
</body>
</html>