<?php
class Lieux
{
	const table_name = 'Lieux';

	private $Id;
	private $Img;
	private $Nom;
	private $Backgd;
	private $Position;
	private $Visible;

	public function byId($id)
	{
		$values = array(
			':id' => $id,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE id=:id';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		
	}

	public function byNom($Nom)
	{
		$values = array(
			':Nom' => $Nom,
			);
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Nom=:Nom';
		return db::execQuery($sql, $values, db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		
	}

	public function byVisible()
	{
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name.'
		WHERE Visible = 1';
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		
	}

	public function byVisibleWithDevice()
	{
		$sql = "SELECT Lieux.Id, Lieux.Nom,Lieux.Img FROM Lieux 
		inner join Device on Device.Lieux_ID= Lieux.Id
		where Lieux.Visible = 1 and Device.Visible = 1
		group by nom
		order by Position";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public function withHistoric()
	{
		$sql = "SELECT Lieux_Id, Nom FROM Temperature 
		inner join Lieux on Lieux.Id = Temperature.Lieux_Id
		GROUP BY Lieux_Id ";
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public function GetAll()
	{
		$sql = 'SELECT ' . db::getColumnName(self::table_name) . '
		FROM '.self::table_name;
		return db::execQuery($sql, [], db::FETCH_TYPE_ALL);
		
	}

	public function SaveLieux($Id, $Name, $Visible, $Position, $Img)
	{
		if ($Id == "")
		{
			$values = array(
				':Name' => $Name,
				':Visible' => $Visible,
				':Img' => $Img
				);

			$sql = "INSERT INTO Lieux (Nom,Visible,Position, Img) select :Name, :Visible, max(Position)+1 as max, :Img from Lieux";			
			db::execQuery($sql, $values);
			
			$msg = "La pièce ".$Name." a bien été ajoutée";
			$value =  Array( "msg"=>$msg,"clear"=>"on");
			return json_encode($value);
		}
		else
		{
			$values = array(
				':id' => $Id,
				':Name' => $Name,
				':Visible' => $Visible,
				':Position' => $Position,
				':Img' => $Img
				);

			$sql = "UPDATE Lieux set Nom=:Name, Visible=:Visible, Position=:Position, Img=:Img where ID = :id";
			db::execQuery($sql, $values);

			$msg = "La pièce ".$Name." a bien été mise à jour";
			$value =  Array( "msg"=>$msg,"clear"=>"on");
			return json_encode($value);
		}
	}

	public function DeleteLieux($Lieuxid,$piece_name)
	{
		$values = array(
			':Lieuxid' => $Lieuxid
			);

		$sql = "SELECT count(*) as nb FROM Device where Lieux_ID =:Lieuxid";	
		
		$NbDevice = db::execQuery($sql, $values);


		foreach($NbDevice as $donnees)
		{			
			$nb = $donnees['nb'];
		}
		
		/*if ($piece_name == "")
		{
			$msg = "Veuillez selectionner une pièce ";
			$value =  Array( "msg"=>$msg,"clear"=>"on");
			return json_encode($value);
		}
		elseif ($nb == 0)
		{*/
		if ($Lieuxid)
		{
			$values = array(
				':Lieuxid' => $Lieuxid
				);
			$sql = "DELETE FROM Lieux where Id =:Lieuxid";
			db::execQuery($sql, $values);
			
			$msg = "La pièce ".$piece_name." a bien été supprimée";
			$value =  Array( "msg"=>$msg,"clear"=>"on");
			return json_encode($value);
		}
		else
		{
			$msg = "Impossible de supprimer cette pièce, supprimez tout d'abord les éléments contenus dans celle-ci ";
			$value =  Array( "msg"=>$msg,"clear"=>"on");
			return json_encode($value);			
		}
	}

	public function get_Id()
	{
		return $this->Id;
	}

	public function get_Img()
	{
		return $this->Img;
	}

	public function get_Name()
	{
		return $this->Nom;
	}

	public function get_Backgd()
	{
		return $this->Backgd;
	}

	public function get_Position()
	{
		return $this->Position;
	}

	public function get_Visible()
	{
		return $this->Visible;
	}
}
?>


