<?php
class db
{
	private
	static $_instance,
	$_host =  "localhost",
	$_user = "{{usersql}}",
	$_pwd = "{{pwdsql}}",
	$_bdd = "{{bddsql}}",
	$_connection;

	const FETCH_TYPE_ROW = 0;
	const FETCH_TYPE_ALL = 1;

	public function __construct()
	{  
		try
		{
			self::$_connection = new PDO('mysql:host='.self::$_host.';dbname='.self::$_bdd.';charset=utf8', self::$_user, self::$_pwd, array(PDO::ATTR_PERSISTENT => true));
		}
		catch (Exception $e)
		{
			echo "Connection sql failed";
		}
	}

	public function getInstance()
	{
		if(is_null(self::$_instance))
        {
			self::$_instance = new db();
		}
		return self::$_connection;
	}
	
	private function getDataQuery($query,$param)
	{
		$QueryPrep =  self::getInstance()->prepare($query);

		if(empty($param))
		{		
			$QueryPrep -> execute();
		}
		else
		{
			$QueryPrep -> execute($param);
		}

		//return json_encode(self::getDataQuery($QueryPrep));
		//return self::ResultToJsonArray($QueryPrep);
		return $QueryPrep;
	}

	public function QuoteValue($value)
	{
		return self::getInstance()->quote($value);
	}
	
	
	public function execQuery($query, $param=[], $_fetchType = self::FETCH_TYPE_ALL, $_fetch_param = PDO::FETCH_ASSOC, $_fetch_opt = NULL)
	{
		$querylower = strtolower($query);
		$sqlcommand = strtok($querylower, ' ');
		$host = $_SERVER['HTTP_HOST'];
	 	if (($sqlcommand == "select") or (strpos($querylower, "insert into connectlog") !== False) or (strpos($querylower, "update user set lastlog") !== False) or ($host == "localhost" or $host == "127.0.0.1") or (isset($_SESSION['userIsAdmin']) and $_SESSION['userIsAdmin'] == 1))  /* ((($sqlcommand == "insert") or ($sqlcommand == "delete") or ($sqlcommand == "update")) and (isset($_SESSION['userIsAdmin']) and $_SESSION['userIsAdmin'] == 1)) )*/
		{
			$resultQuery = self::getDataQuery($query,$param);
			
			if ($resultQuery !=false)
			{
					if ($_fetchType == self::FETCH_TYPE_ROW)
					{
						if ($_fetch_opt == NULL)
						{

							$res = $resultQuery->fetch($_fetch_param);

						}
						else if ($_fetch_param == PDO::FETCH_CLASS)
						{

							$res = $resultQuery->fetchObject($_fetch_opt);
						}
					}
					else
					{
						if ($_fetch_opt == NULL)
						{
							$res = $resultQuery->fetchAll($_fetch_param);
						}
						else
						{
							$res = $resultQuery->fetchAll($_fetch_param, $_fetch_opt);
						}
					}
			
				$errorInfo = $resultQuery->errorInfo();

				if ($errorInfo[0] != 0000)
				{
					throw new Exception('[MySQL] Error code : ' . $errorInfo[0] . ' (' . $errorInfo[1] . '). ' . $errorInfo[2]);
				}
				return $res;
			} 
		}
		else
		{
			return false;
		}
	}

	public function execQueryDebug($query,$param)
	{
		self::getDataQuery($query,$param)->debugDumpParams();
		//return json_encode(self::getDataQuery($QueryPrep));
		//return $QueryPrep;
	}
	
	public function getNbResult($query,$param)
	{		
		return self::getDataQuery($query,$param)->rowCount();
	}

	public function getColumnName($table_name)
	{
		$values = array(
			':table_name' => $table_name,
			);
		$json_col_name = self::execQuery("SELECT column_name from INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME =:table_name",$values);
		return  self::JsonToString(json_encode($json_col_name));
	}

	public function JsonToString($data)
	{	
		$fields = array();
		$obj = json_decode($data,true);
		foreach ($obj as $key => $value) {
			foreach ($value as $id => $val) {
				//return $val;
				$fields[] = $val;
			}
		}
		return implode(', ', $fields);
	}

	public function ResultToJsonArray($data)
	{
		$JSON = array();
		if ($data)
		{
			/*foreach($data as $donnees=> $value)
			{	
				foreach ($value as $id => $val) {
					if ($id == "Date")
					{
						$val = DateDifferenceToString($val);
					}
					$row_array[$id] = $val;
				}
				array_push($JSON,$row_array);
				echo json_encode($JSON);
			}*/
			echo json_encode($data);
		}
	}

	public function save($objects)
	{
//		$productsArray = get_object_vars($object);
		var_dump($objects);
		foreach($objects as $key => $value)
		{
			var_dump(is_object($objects));
		}
		//var_dump($object);

		//echo "INSERT INTO ".$object::table_name." SET ";
	}
}
?>
