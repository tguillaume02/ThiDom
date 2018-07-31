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

function setJsonAttr($_attr, $_key, $_value = null) {
  if ($_value === null && !is_array($_key)) {
    if ($_attr != '' && is_json($_attr)) {
      $attr = json_decode($_attr, true);
      unset($attr[$_key]);
      $_attr = json_encode($attr, JSON_UNESCAPED_UNICODE);
    }
  } else {
    if ($_attr == '' || !is_json($_attr)) {
      $attr = array();
    } else {
      $attr = json_decode($_attr, true);
    }
    if (is_array($_key)) {
      $attr = array_merge($attr, $_key);
    } else {
      $attr[$_key] = $_value;
    }
    $_attr = json_encode($attr, JSON_UNESCAPED_UNICODE);
  }
  return $_attr;
}

function getPost($post)
{
  if(isset($_POST[$post]))
  {
    return $_POST[$post];
  }
  else
  {
    return "";
  }
}

function strposa($haystack, $needle, $offset=0)
{
  if(!is_array($needle))
  {
    $needle = array($needle);
  }
  foreach($needle as $query)
  {
    if(strpos($haystack, $query, $offset) !== false)
    {
      return true; 
    }
  }
  return false;
}

function removeAccent($string)
{
  /*$transliterator = Transliterator::create(
      'NFD; [:Nonspacing Mark:] Remove; NFC;'
  );

  return $transliterator->transliterate($string);*/
	$string = utf8_decode($string);     
    $string = strtr($string, utf8_decode('çàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'); 
    $string = strtolower($string); 
    return utf8_encode($string); 
}
?>
