<?php
include_once('../../Security.php');

header('Content-Type: application/json');


  // Variable Connexion  
  $host       = ""; // IP DE LA LIVEBOX
  $user       = ""; // USE DE LA LIVEBOX PAR DEFAUT ADMIN
  $pass       = ""; // MOT DE PASSE DE LA LIVEBOX
  
  // Variable Configuration   
  $cookieDir  = sys_get_temp_dir() ; 
  $cookieFile = tempnam($cookieDir, "LIVEBOX");
  $post = '{"parameters":{}}' ;
  $type = 8;
  $Name_Script = "Livebox";

  
  
  // Login 
  $cLogin = curl_init(); 
  curl_setopt_array($cLogin, array(
      CURLOPT_RETURNTRANSFER  => 1,
      CURLOPT_URL             => 'http://'.$host.'/authenticate?username='.$user.'&password='.$pass,
      CURLOPT_HTTPHEADER      => array('Content-type: application/json') ,
      CURLOPT_COOKIEJAR       => $cookieFile,
      CURLOPT_POST            => 1
  ));

  $auth_result = curl_exec($cLogin);
  $auth=json_decode($auth_result); 
  $contextID = $auth->data->contextID ;  
  curl_close($cLogin);

  // Status
  $cStatus = curl_init();
  curl_setopt_array($cStatus, array(
      CURLOPT_RETURNTRANSFER  => 1,
      CURLOPT_URL           => 'http://'.$host.'/sysbus/NeMo/Intf/data:getMIBs',
      CURLOPT_HTTPHEADER    => array('Content-type: application/json', 'X-Context: '.$contextID),
      CURLOPT_COOKIEFILE    => $cookieFile,
      CURLOPT_POST          => 1, 
      CURLOPT_POSTFIELDS        => $post
  ));



  // Reboot
  $cReboot = curl_init();
  curl_setopt_array($cReboot, array(
      CURLOPT_RETURNTRANSFER  => 1,
      CURLOPT_URL           => 'http://'.$host.'/sysbus/NeMo/Intf/data:getMIBs',
      CURLOPT_HTTPHEADER    => array('Content-type: application/json', 'X-Context: '.$contextID),
      CURLOPT_COOKIEFILE    => $cookieFile,
      CURLOPT_POST          => 1, 
      CURLOPT_POSTFIELDS        => $post
  ));

  if (isset($_POST['act']))
  {
    $act = $_POST['act'];

     
    if ($act == "rebootLivebox")
    {
      $reboot = curl_exec($cReboot);
      curl_close($cReboot); 
    } 

    if ($act == "loadData")
    {
      $status = curl_exec($cStatus);
      curl_close($cStatus);   
      unlink($cookieFile) ; 
      $parsed_json = json_decode($status);
      $UpStream = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'UpstreamCurrRate'};
      $DownStream = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'DownstreamCurrRate'};
      $LastChange = $parsed_json->{'result'}->{'status'}->{'dsl'}->{'dsl0'}->{'LastChange'};
      $UpStream = round($UpStream/1000,2) ;      
      $DownStream = round($DownStream/1000,2);
      $jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"); 
      $mois = array("","Janv","Févr","Mars","Avr","Mai","Juin","Juill","Août","Sept","Oct","Nov","Déc"); 
      $w = date("w", strtotime('now -'.$LastChange.' Seconds')); 
      $j = date("j", strtotime('now -'.$LastChange.' Seconds')); 
      $m = date("n", strtotime('now -'.$LastChange.' Seconds')); 
      $Y = date("Y", strtotime('now -'.$LastChange.' Seconds')); 
      $LastChange = $jour[$w]." ".$j." ".$mois[$m]." ".$Y."-".date("H:i", strtotime('now -'.$LastChange.' Seconds')); 




      /*$req = execute_sql("UPDATE cmd_device SET Value ='$UpStream' ,Etat ='$UpStream' where Nom = 'Up' ");
      $req1 = execute_sql("UPDATE cmd_device SET Value ='$DownStream' ,Etat ='$DownStream' where Nom = 'Down' ");*/
      Update_Device_Value($UpStream,$UpStream,"Up",$type,$Name_Script);
      Update_Device_Value($DownStream,$DownStream,"Down",$type,$Name_Script);
      Update_Device_Value($LastChange,$LastChange,"Last Change",$type,$Name_Script);

      $JSON = array();
      $row_array["UpStream"] = $UpStream ;
      $row_array["DownStream"] = $DownStream;
      $row_array["Last Change"] = $LastChange;
      array_push($JSON,$row_array);
      echo  json_encode($JSON);


    }

  }
?>
