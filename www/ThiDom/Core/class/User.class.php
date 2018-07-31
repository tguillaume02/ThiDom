<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class User
{
	private $UserName;
	private $UserIsAdmin;
	const table_name = 'User';

	public function CheckUser($Login, $pwd)
	{
		$values = array(
			':Login' => $Login,
			':pwd' => $pwd,
			);
		$sql = 'SELECT Id, UserName, UserPass, UserIsAdmin
		FROM '.self::table_name.'
		WHERE UserName=:Login AND UserPass=:pwd';
		$result =  db::execQuery($sql, $values);
		$nb_row = db::getNbResult($sql, $values);

		if ($nb_row == 1 )
		{ 
			return $result;
		}
		else
		{
			return False;
		}
	}

	public function getUserById($Id)
	{
		$values = array(
			':Id' => $Id,
			);
		$sql = 'SELECT Id, UserName, UserPass, UserIsAdmin
		FROM '.self::table_name.'
		WHERE Id=:Id';
		return db::execQuery($sql, $values);
	}

	public function getUserByHash($hash)
	{
		$values = array(
			':Hash' => $hash,
			);
		$sql = 'SELECT Id, UserName, UserPass, UserHash, UserIsAdmin
		FROM '.self::table_name.'
		WHERE UserHash=:Hash';
		return db::execQuery($sql, $values);

	}

	public function getUser()
	{ 
		$sql = 'SELECT Id, UserHash, UserName, LastLog, UserIsAdmin
		FROM '.self::table_name;
		return db::execQuery($sql);
	}

	public function getUserName($UserId)
	{
		$values = array(
			':UserId' => $UserId,
			);
		$sql = 'SELECT UserName
		FROM '.self::table_name.'
		WHERE Id=:UserId';
		return db::execQuery($sql, $values);
	}

	public function getUserAdmin($UserId)
	{
		$values = array(
			':UserId' => $UserId,
			);
		$sql = 'SELECT UserName, UserIsAdmin
		FROM '.self::table_name.'
		WHERE Id=:UserId';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public function getNewHash()
	{
		$hash =  bin2hex(openssl_random_pseudo_bytes(16));
		$hashArray = array('hash' => $hash);
		return json_encode($hashArray);
	}

	public function SaveUser($Id, $Name, $Password, $Hash)
	{
		if ($Password != "***********")
		{
			$Password = hash('sha256', $Password);
		}		
		else
		{
			$Password = $_SESSION['userPass'];
		}

		if ($Id == "")
		{		
			$values = array(
				':UserName' => $Name,
				':UserPass' => $Password,
				':UserHash' => $Hash
			);
			$sql = "INSERT INTO User (UserName, UserPass, UserHash) VALUES (:UserName, :UserPass, :UserHash)";
			db::execQuery($sql,$values);

			$msg = "L'utilisateur ".$Name." a bien été ajouté";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
		else
		{		
			$values = array(
				':Id' => $Id,
				':UserName' => $Name,
				':UserPass' => $Password,
				':UserHash' => $Hash
			);
			$sql = "UPDATE User SET UserName=:UserName, UserPass=:UserPass, UserHash=:UserHash where Id=:Id";
			db::execQuery($sql,$values);

			$msg = "L'utilisateur ".$Name." a bien été mis à jour";
			$value = Array( "msg"=>$msg, "clear"=>"on");
			return json_encode($value);
		}
	}

	public function setAccess($User, $Ip, $City, $Region, $Country, $IdentificationType)
	{
		$values = array(
			':ip' => $Ip,
			':city' => $City,
			':region' => $Region,
			':country' => $Country,
			':dateAcces' => date('Y-m-d H:i:s'),
			':indentificationType' => $IdentificationType . " For ". $User
			);

		$sql = "INSERT INTO ConnectLog VALUES (:ip, :city, :region, :country, :dateAcces, :indentificationType)";
		db::execQuery($sql, $values);

		$values = array(
			':data' => $Ip . " - ". date('d-m-Y H:i:s'),
			':user' => $User
			);

		$sql = "UPDATE User set LastLog =:data where UserName =:user";
		return db::execQuery($sql, $values);

	}
	
	public function logout()
	{
		$_SESSION = array();
		session_unset();
		session_destroy();
		setcookie("auth", "", time()-3600, '/', '', true, true);
		setcookie("date", "", time()-3600, '/', '', true, true);
		$host  = $_SERVER['HTTP_HOST'];
		$extra = 'login.php';

		$redirect = "$host/ThiDom/$extra";

		if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
		{
			$redirect = "http://".$redirect;
		}
		else
		{
			$redirect = "https://".$redirect;
		}
		header("Location: $redirect");
		exit();
	}

	public function FormCheckUser($act = "", $user = "", $pass_user = "", $remember = "")
	{
		$error = "";

		if (isset($act) ? $act : '' == "out")
		{
			self::logout();
		}
		else
		{
			if (!empty($user) && !empty($pass_user))
			{	
				$User=$user;
				$Password=hash('sha256',$pass_user);
				$UserName = stripslashes($User);
				$password = stripslashes($Password);
				$ResultUser = self::CheckUser($UserName,$password);
				if ($ResultUser)
				{
					foreach($ResultUser as $donnees)
					{
						$_SESSION['userName'] = $UserName;
						$_SESSION['userPass'] = $Password;
						$_SESSION['userId'] = $donnees["Id"];
						$_SESSION['userIsAdmin'] = $donnees["UserIsAdmin"];
						if ($remember == "true")
						{							
							$key = sha1($donnees["UserName"].$donnees["UserPass"].$_SERVER["REMOTE_ADDR"]);
							setcookie('auth', $donnees["Id"]. '-----'.$key, time() + 3600 * 24 * 7, '/','',true,true);
						}
					}
					self::logAccess($user, "By Password");
					echo json_encode(array('result' => 'index.php'));
				}
				else
				{
					throw new Exception('Mot de passe ou nom d\'utilisateur incorrect',400);
				}
			}
		}
	}

	public function hashAuthentification($hash="")
	{
		$auth = ['',''];
		$ResultUser = "";

		if (!empty($hash))
		{
			$ResultUser = self::getUserByHash($hash);
			foreach($ResultUser as $donnees)
			{
				if ($hash == $donnees["UserHash"])
				{
					$_SESSION['userName']=$donnees["UserName"];
					$_SESSION['userPass']=$donnees["UserPass"];
					$_SESSION['userId']=$donnees["Id"];
					$_SESSION['userIsAdmin']=$donnees["UserIsAdmin"];					
					self::logAccess($donnees["UserName"], "By HASH");
					return true;				
				}
				else
				{
					self::logout();
					return false;				
				}
			}
		}
		elseif (isset($_COOKIE['auth']))
		{
			$auth = explode('-----',$_COOKIE['auth']);
			$ResultUser = self::getUserById($auth[0]);

			foreach($ResultUser as $donnees)
			{
				$key = sha1($donnees["UserName"].$donnees["UserPass"].$_SERVER["REMOTE_ADDR"]);

				if ($key == $auth[1])
				{
					$_SESSION['userName']=$donnees["UserName"];
					$_SESSION['userPass']=$donnees["UserPass"];
					$_SESSION['userId']=$donnees["Id"];
					$_SESSION['userIsAdmin']=$donnees["UserIsAdmin"];

					setcookie('auth', $donnees["Id"]. '-----'.$key, time() + 3600 * 24 * 7, '/','',true,true);
					self::logAccess($donnees["UserName"], "By Cookie/Session");
					return true;				
				}
				else
				{
					self::logout();
					return false;				
				}
			}
		}
		else
		{
			self::logout();
			return false;
		}
	}

	public function getName()
	{
		return $this->UserName;
	}

	public function getIsAdmin()
	{
		return $this->UserIsAdmin;
	}

	public function isLogged($hash="")
	{
		if ($hash == "local")
		{
			$_SESSION['userIsAdmin'] = 1;
			return true;
		}
		else
		{
			if (isset($_COOKIE['auth']) || !empty($hash))
			{
				if(self::hashAuthentification($hash))
				{
					//
					//$_SESSION['user'] = $user;
				}
				else
				{
					return self::logout();
					//header('Location: /login.php');
				}
			}
			elseif (isset($_SESSION['userName']) && isset($_SESSION['userPass']))
			{

				$ResultUser = self::CheckUser($_SESSION['userName'],$_SESSION['userPass']);
				if ($ResultUser)
				{
					foreach($ResultUser as $donnees)
					{
						$_SESSION['userName'] = $donnees["UserName"];
						$_SESSION['userPass'] = $donnees["UserPass"];
						$_SESSION['userId'] = $donnees["Id"];
						$_SESSION['userIsAdmin'] = $donnees["UserIsAdmin"];
					}
					return true;
				}
				else
				{
					return self::logout();
				}
			}
			else
			{
				return self::logout();	
			}
		}
	}

	public function logAccess($user, $identificationType)
	{
		if ((isset($_COOKIE['date']) && $_COOKIE['date'] <= date("YmdHis") - 10000) || empty($_COOKIE['date']))
		{
			$visitorIp = $_SERVER['REMOTE_ADDR'];
			$curl = curl_init(); 
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_URL             => 'http://ip-api.com/json/'.$visitorIp,
				CURLOPT_HTTPHEADER      => array('Content-type: application/json'),
				CURLOPT_TIMEOUT 		=> 30
				));

			$response  = json_decode(curl_exec($curl));
			if ($response)
			{
				$city = $response->city;
				$region = $response->regionName;
				$country = $response->country;
				$ip = $response->query;
				$isp = $response->isp;
				curl_close($curl);

				self::setAccess($user, $ip, $city, $region, $country, $identificationType);

				setcookie('date',date("YmdHis"), time() + 3600, '/','',true,true);	
			}
		}
	}
}
?>
