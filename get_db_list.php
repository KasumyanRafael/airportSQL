<?php
if (!isset($_POST['user'])){
	die('Нет авторизации!');
}

require_once('connect_pdo.php');
$res = $dbc->query('show databases');

$columns =  $res->columnCount();

echo "<table border='1'><tr><td><b>Database name</b></td></tr>";
	while($ar = $res->fetch()){
		echo "<tr><td>$ar[0]</td></tr>";
	}
	
?>