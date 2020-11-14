<?php
require_once '../Core/Security.php';



$sql = 'ALTER TABLE Planning CHANGE COLUMN Days Days VARCHAR(100) NULL DEFAULT NULL';
return db::execQuery($sql, [], db::FETCH_TYPE_ALL);


shell_exec('sudo a2enmod expires');
shell_exec('sudo a2enmod http2');
shell_exec('sudo a2enmod headers');



?>