<?php
// Подключение к БД 
// mysqli_connect() - подключение к БД
if (!$dbc = mysqli_connect('localhost','airport','airport', 'airport')){
	$er = mysqli_connect_error();
	echo 'Ошибка' . $er[0];
	die();
}

?>