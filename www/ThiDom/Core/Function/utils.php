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
    $_attr = json_encode($attr/*, JSON_UNESCAPED_UNICODE*/);
    }
  } else {
    if (($_attr == '' || $_value === null) || !is_json($_attr)) {
      $attr = array();
    } else {
      $attr = json_decode($_attr, true);
    }
    if (is_array($_key)) {
      $attr = array_merge($attr, $_key);
    } else {
      $attr[$_key] = $_value;
    }
  $_attr = json_encode($attr/*, JSON_UNESCAPED_UNICODE*/);
  }
  return $_attr;
}

function getParameter($parameter)
{
  if(filter_input(INPUT_POST, $parameter))
  {
    return filter_input(INPUT_POST, $parameter);
  }
  elseif(filter_input(INPUT_GET, $parameter))
  {
    return filter_input(INPUT_GET, $parameter);
  }
  elseif ((filter_input(INPUT_GET, $parameter) == 0 || filter_input(INPUT_POST, $parameter) == 0) && (filter_input(INPUT_GET, $parameter) != "" || filter_input(INPUT_POST, $parameter) != ""))
  {
	return "0";	
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

function ls($folder = "", $pattern = "*", $recursivly = false, $options = array('files', 'folders')) {
	if ($folder) {
		$current_folder = realpath('.');
		if (in_array('quiet', $options)) {
			// If quiet is on, we will suppress the 'no such folder' error
			if (!file_exists($folder)) {
				return array();
			}
		}
		if (!is_dir($folder) || !chdir($folder)) {
			return array();
		}
	}
	$get_files = in_array('files', $options);
	$get_folders = in_array('folders', $options);
	$both = array();
	$folders = array();
	// Get the all files and folders in the given directory.
	
  if($get_files) 
  {
    $both = glob($pattern, GLOB_BRACE + GLOB_MARK);
  }
	if ($recursivly || $get_folders) {
		$folders = glob("*", GLOB_ONLYDIR + GLOB_MARK);
	}
	//If a pattern is specified, make sure even the folders match that pattern.
	$matching_folders = array();
	if ($pattern !== '*') {
		$matching_folders = glob($pattern, GLOB_ONLYDIR + GLOB_MARK);
	}
	//Get just the files by removing the folders from the list of all files.
	$all = array_values(array_diff($both, $folders));
	if ($recursivly || $get_folders) {
		foreach ($folders as $this_folder) {
			if ($get_folders) {
				//If a pattern is specified, make sure even the folders match that pattern.
				if ($pattern !== '*') {
					if (in_array($this_folder, $matching_folders)) {
						array_push($all, $this_folder);
					}
				} else {
					array_push($all, $this_folder);
				}
			}
			if ($recursivly) {
				// Continue calling this function for all the folders
				$deep_items = ls($pattern, $this_folder, $recursivly, $options); # :RECURSION:
				foreach ($deep_items as $item) {
					array_push($all, $this_folder . $item);
				}
			}
		}
	}
	if ($folder && is_dir($current_folder)) {
		chdir($current_folder);
	}
	if (in_array('datetime_asc', $options)) {
		global $current_dir;
		$current_dir = $folder;
		usort($all, function ($a, $b) {
			return filemtime($GLOBALS['current_dir'] . '/' . $a) < filemtime($GLOBALS['current_dir'] . '/' . $b);
		});
	}
	if (in_array('datetime_desc', $options)) {
		global $current_dir;
		$current_dir = $folder;
		usort($all, function ($a, $b) {
			return filemtime($GLOBALS['current_dir'] . '/' . $a) > filemtime($GLOBALS['current_dir'] . '/' . $b);
		});
	}
	return $all;
}

function get_iconsButtonList($id, $defaultIcons) {
  $iconslist = "";  
  $display = $defaultIcons == ""? "display:none" : "";
  $iconslist = '<div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 form-group dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Icons
  <span class="caret"></span></button>
  <img id="defaulticons_'.$id.'" src="Core/pic/Widget/'.$defaultIcons.'" alt="defaulticons" style="width: 50px; height: 50px;'.$display.'">
  <ul class="dropdown-menu" style="overflow: scroll;max-height: 408px;">
      <li>
          <a href="#" onclick="$(\'#CustomIcons_'.$id.'\').val(\'\')" title="">Default</a>
      </li>';
  if ($handle = opendir(dirname(__FILE__).'/../pic/Widget/')) {
          /* Ceci est la façon correcte de traverser un dossier. */
          while (false !== ($entry = readdir($handle)))
          {
              if ($entry != "." && $entry != ".." && (strpos($entry, '_on') === false && strpos($entry, '_On') === false && strpos($entry, '_off') === false && strpos($entry, '_Off') === false))
              {
                  $filename = substr($entry, 0, strrpos($entry, "."));
                  $iconslist .= "<li><a href='#' onclick=\"$('#CustomIcons_$id').val('$filename');$('#defaulticons_$id').attr('src','Core/pic/Widget/$filename');$('#defaulticons_$id').show();\" title=\"$entry\"><img class='img-circle img_btn_device rounded-circle' src='Core/pic/Widget/$entry'>$filename</a></li>";
              }
          }
          closedir($handle);
  }
  $iconslist .=' </ul>
        <input id="CustomIcons_'.$id.'" name="icons" type="text" class="form-control" style="display: none">
      </div>';
  return $iconslist;

}

?>
