<?php
function include_files($folder,$file,$type)
{	
	if ($type === 'js' or $type === 'css')
	{				
		return '<script type="text/javascript" src="'.$folder.'/'.$file.'"></script>';
	}

	if ($type === 'class')
	{				
		return '<script type="text/javascript" src="'.$folder.'/'.$file.'"></script>';
	}

	if ($type === 'php')
	{				
		require_once '../'.$folder.'/'.$file.'.'.$type;
	}
}

function BooleanToString($bool)
{
  return $bool?"on":"off";
}

function DateDifferenceToString($date)
{
  return $date;
  /*$SinceTime = "";
  $datetime1 = new DateTime($date);
  $datetime2 = new DateTime();
  $interval = $datetime1->diff($datetime2);
  $Seconds =  $interval->format('%S');
  $Minutes =  $interval->format('%I');
  $Heures =  $interval->format('%H');
  $Day =  $interval->format('%D');
  $Month =  $interval->format('%M');
  $Year =  $interval->format('%Y');
  if ($Year > 0)
  {
    $SinceTime = $Year." An(s)";          
  }
  else if ($Month > 0)
  {
    $SinceTime = $Month." Mois";   
  }
  else if ($Day > 0)
  {
    $SinceTime = $Day." Jours";  
  }
  else if ($Heures > 0)
  {
    $SinceTime = $Heures." Heures ". $Minutes;   
  }
  else if ($Minutes > 0)
  {
    $SinceTime = $Minutes." Minutes";
  }      
  else if ($Seconds >=0)
  {
    $SinceTime = $Seconds." Seconds";
  }
  return $SinceTime;*/
}

function SpaceToScore($word)
{
  return (str_replace(" ","_",$word));
}

function is_json($_string) {
  return ((is_string($_string) && (is_object(json_decode($_string)) || is_array(json_decode($_string))))) ? true : false;
}

function getJsonAttr($_attr, $_key = '', $_default = '') {
  if ($_key == '') {
    if ($_attr == '' || !is_json($_attr)) {
      return $_attr;
    }
    return json_decode($_attr, true);
  }
  if ($_attr === '') {
    return $_default;
  }
  $attr = json_decode($_attr, true);
  return (isset($attr[$_key]) && $attr[$_key] !== '') ? $attr[$_key] : $_default;
}


?>
