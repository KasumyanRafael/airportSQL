<?php
// Подключение к БД 
try{
	$dbc = new pdo('mysql:host=localhost;dbname=univer', 'root','');
}catch(PDOException $err){
	echo $err->getMessage();
}
?>