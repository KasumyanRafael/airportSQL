<?php
	try{
		$dbc = new pdo('mysql:host=localhost;','root','');
	}catch(PDOException $err){
		echo $err->getMessage();
	}
?>

