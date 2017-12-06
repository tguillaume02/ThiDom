<?php
class Module
{
	const table_name = 'Module_Type';
	private $Id;
	private $ModuleName;

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

	public function get_ModuleName()
	{
		return $this->ModuleName;
	}

}