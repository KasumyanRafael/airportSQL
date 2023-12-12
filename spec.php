<?php
/*
Модуль для работы с таблицей специальностей - spec
*/
require_once('connect_pdo.php');

if(isset($_POST['add'])){
	$name= $_POST['name'];
	$kod = $_POST['kod'];
	
	$sth = $dbc->prepare("insert into spec (name, shifr) values (:name,:kod)");
	try{
		$sth->execute(array(':name'=>$name, ':kod'=>$kod));
	}catch(PDOException $err){
		echo 'Добавление '. $err->getMessage();
	}
}
if(isset($_POST['del'])){
	$id = $_POST['id'];
	$sth = $dbc->prepare("delete from spec where id = :id");
	try{
		$sth->execute(array(':id'=>$id));
	}catch(PDOException $err){
		echo 'Удаление '. $err->getMessage();
	}	
}
if(isset($_POST['save'])){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$kod = $_POST['kod'];
	try{
		$sth = $dbc->prepare("update spec set name = :name, shifr = :kod where id = :id");
		$sth->execute(array(':id'=>$id, ':name'=>$name, ':kod'=>$kod));
	}catch(PDOException $err){
		echo 'Изменение '. $err->getMessage();
	}	
}

$select_str = "select * from spec order by name";
try{
	$dbr = $dbc->query($select_str);
	
	echo "<table border='1'><tr><td>ID</td><td>Наимнование</td><td>Код</td><td>Операция</td></tr>";
	
	while($ar = $dbr->fetch()){
		echo "<tr><td>$ar[0]</td><td>$ar[1]</td><td>$ar[2]</td><td width='240'>
		<form action='' method ='post'>
			<input type='submit' name='del' value='Удалить'>
			<input type='submit' name='edit' value='Изменить'>
			<input type='hidden' name='id' value='$ar[0]' style='width:0;height:0;'>
		
		</form>
		</td></tr>";
	}
	echo "</table>";
}catch(PDOException $err){
	echo 'Запрос '. $err->getMessage();
	
}

if(isset($_POST['edit'])){
	// Изменение данных
	$select_str = "select name, shifr from spec where id = :id";
	$id = $_POST['id'];
	try{
		$sth = $dbc->prepare($select_str);
		$sth->execute(array(':id'=>$id));
		$arr = $sth->fetch();			
	}catch(PDOException $err){
		echo 'Запрос '. $err->getMessage();
	}	

	$name = $arr[0];
	$kod = $arr[1];
	echo "<form action='' method='post' >";
	echo "<p>Наименование <input type='text' name='name' value='$name'></p>";
	echo "<p>Код <input type='text' name='kod' value='$kod'></p>";
	echo "<input type='hidden' name='id' value='$id'>";
	echo "<p><input type='submit' name='save' value='Сохранить'><p>";
	echo "</form>";
}else{
	// добавление данных
	echo "<form action='' method='post' >";
	echo "<p>Наименование <input type='text' name='name' value=''></p>";
	echo "<p>Код <input type='text' name='kod' value=''></p>";
	echo "<p><input type='submit' name='add' value='Добавить'><p>";

	echo "</form>";
}
?>

<script >


</script>