<?php
// include database
include_once ("MysqliDb.php");

// server,username,password,database
$db = new Mysqlidb ( 'localhost', 'root', '', 'insided' );
if (! $db) {
	die ( "Database error..." );
}
?>