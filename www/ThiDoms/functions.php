<?php
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 	



function Update_Device_Value($Value,$Etat,$Name1,$type,$Name2) 
{ 

	$req = execute_sql("UPDATE cmd_device,Device SET cmd_device.Value ='$Value' , cmd_device.Etat ='$Etat', cmd_device.Date =now() where cmd_device.Nom = '$Name1' and Device.Type_ID ='$type' and Device.Nom = '$Name2'");
} 	
?>