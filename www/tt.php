
<?php
$YearNow = date("Y");
$Bisvisible ="22";
$temp_year = 2013;
				if ($temp_year <= $YearNow-2)
			{
				$Bisvisible = "false";
			}
			else
			{
				$Bisvisible = "true";
			}
				echo $Bisvisible;


?>
