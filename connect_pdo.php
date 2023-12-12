<?php
// Подключение к БД 
try{
	$dbc = new pdo('mysql:host=localhost;dbname=airport', 'root','');
}catch(PDOException $err){
	echo $err->getMessage();
}
?>