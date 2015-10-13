	<?php	
	include_once('Security.php');
	

	$sth = execute_sql("SELECT @temp_year:=EXTRACT(YEAR FROM date) AS temp_year, temp, UNIX_TIMESTAMP(replace(date,EXTRACT(YEAR FROM date),2015)) as date FROM Temperature");
$rows = array();
while($r = $sth->fetch_array(MYSQLI_ASSOC)) {
    $rows[] = $r;
}
print json_encode($rows);

?>