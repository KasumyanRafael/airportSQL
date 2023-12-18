<?php
function create_database(){
	/*
		Создание структуры базы данных
		Вход: нет
		Выход: нет
	*/
	global $dbc;
	echo "Создание БД! <br> Внимание!<br> Не запускать на действующей системе! <br>";
	
	// Пересоздание БАЗЫ ДАННЫХ (удаление, а затем создание)
	try{
		$dbc->exec('drop database if exists airport');
		$dbc->exec('create database airport');
		$dbc->exec('use airport');
	}catch(PDOException $err){
		echo $err->getMessage();
	}

	// ======== Создание таблицы users - пользователи системы
	try{
		$query_str = 'create table if not exists users (id int unsigned primary key auto_increment
		                , passwordSeria char(4) primary key NOT NULL
						, passwordNumber char(4) primary key NOT NULL
						, name varchar(16) NOT NULL
						, surname varchar(16) NOT NULL
						, birthdayDate date NOT NULL
						, password char(20) default"passwordNumber"
						, gender tinyint NOT NULL
						, userType enum ("1","2","3","4","5","6") default "1"
						, userAddAccess` enum ("0","1") default"0")';
		$dbc->exec($query_str);
		echo 'Таблица users создана!<br>';
	}catch(PDOException $err){
		echo "users".$err->getMessage();
	}
	// ======== Создание таблицы userContacts - контакты пользователей
	try{
		$query_str = 'create table if not exists userContacts (users_passwordSeria char(4) NOT NULL,
		, users_passwordNumber char(6) NOT NULL
		, email varchar(20) NOT NULL
		, phoneNumber varchar(16) NOT NULL
		, FOREIGN KEY (`users_passwordSeria`) references `users` (`passwordSeria`) on delete cascade,
		, FOREIGN KEY (`users_passwordNumber`) references `users` (`passwordNumber`) on delete cascade))';
		$dbc->exec($query_str);

		echo 'Таблица userContacts создана!<br>';
	}catch(PDOException $err){
		echo "userContacts ".$err->getMessage();
	}
	
	// ======== Создание таблицы services - услуги аэропорта
	try{
		$query_str = 'create table if not exists services (idServices int unsigned primary key AUTO_INCREMENT
		, serviceName varchar(20) NOT NULL
		, price_One int unsigned NOT NULL
		, measureUnit varchar(1) NOT NULL
		, UNIQUE KEY(`serviceName`))';
		$dbc->exec($query_str);

		
		echo 'Таблица платных услуг создана!<br>';
	}catch(PDOException $err){
		echo "services ".$err->getMessage();
	}
	
	// ======== Создание таблицы airline - авиакомпании, осуществляющие рейсы в аэропорт Беслан
	try{
		$query_str = 'create table if not exists airline (IATA-code char(2) primary key not null
		, airlineName varchar(10) not null
		, unique key(`airlineName`))';
		$dbc->exec($query_str);
		echo 'Таблица airline создана!<br>';
	}catch(PDOException $err){
		echo "airline".$err->getMessage();
	}
	
	// ======== Создание таблицы aircrafts- модели воздушных судов, которые в силу своих характеристик может принять аэропорт
	try{
		$query_str = 'create table if not exists aircrafts (idAircrafts int unsigned primary key AUTO_INCREMENT
		, modelName varchar(10) not null
		, averageRange int unsigned not null
		, unique key(`modelName`))';
		$dbc->exec($query_str);

		echo 'Таблица aircrafts создана!<br>';
	}catch(PDOException $err){
		echo "aircrafts".$err->getMessage();
	}

	// ======== Создание таблицы fleet - флот авиакомпаний
	try{
		$query_str = 'create table fleet (countryReg char(2) primary key not null
						, numberReg varchar(6) primary key not null
						, airline_IATA-code char(2) not null
						, aircrafts_idAircrafts int unsigned not null
						, businessSeats smallint unsigned default"0"
						, economySeats smallint unsigned default"0"
						, cargoSlots smallint unsigned default"0"
						, madeOn date not null
						, FOREIGN KEY (`airline_IATA-code`) references `airline` (`IATA-code`) on delete cascade
						, FOREIGN KEY (`aircrafts_idAircrafts`) references `aircrafts` (`idAircrafts`) on delete cascade
						)';
		$dbc->exec($query_str);
		echo 'Таблица судов создана!<br>';
	}catch(PDOException $err){
		echo "fleet " . $err->getMessage();
	}


		
	// ======== Создание таблицы ordersToService - заказы
	try{
		$query_str = 'create table if not exists ordersToService (orderId int unsigned primary key AUTO_INCREMENT
						, services_idServices tinyint unsigned not null
						, amount tinyint unsigned default "1",
						, users_passwordSeria char(4) not null
						, `users_passwordNumber` char(6) not null
						, FOREIGN KEY (`users_passwordSeria`) references `users` (`passwordSeria`) on delete cascade
						, FOREIGN KEY (`users_passwordNumber`) references `users` (`passwordNumber`) on delete cascade
						, FOREIGN KEY (`services_idServices`) references `services` (`idServices`) on delete cascade
						)';
		$dbc->exec($query_str);
		echo 'Таблица заказов создана!<br>';
	}catch(PDOException $err){
		echo "ordersToService ".$err->getMessage();
	}	

	
	// ======== Создание таблицы destinations - cписок всех аэропортов, куда можно улететь
	try{
		$query_str = 'create table if not exists destinations (`IATA-dest` char(3) primary key not null
						, cityName varchar(18) default "Владикавказ(Беслан)"
						, country varchar(30) default "Россия"
						, unique key(`cityName`)
						)';
		$dbc->exec($query_str);
		echo 'Таблица destinations создана!<br>';
	}catch(PDOException $err){
		echo "destinations ".$err->getMessage();
	}	

// ======== Создание таблицы route - маршруты
	try{
		$query_str = 'create table if not exists route (airline_IATA-code char(2) primary key not null
						, number int unsigned primary key not null
						, destinations_IATA-dest char(3) not null
						, RouteType tinyint unsigned default "1"
						, ArrTime time not null
						, DepTime time not null
						, FlightDays varchar(7) default"1234567"
						, Fleet_CountryReg char(2) not null
						, Fleet_NumberReg varchar(6) not null
						, FirstFlight date not null
						, LastFlight date not null
						, AverageRouteDistance int unsigned not null
						, AverageDuration time not null
						, EconomyPrice smallint unsigned default "0"
						, BusinessPrice` smallint unsigned default "0"
						, CargoPrice smallint unsigned default "0"
						, FOREIGN KEY (`airline_IATA-code`) references `airline` (`IATA-code`) on delete cascade
						, FOREIGN KEY (`destinations_IATA-dest`) references `destination` (`IATA-dest`) on delete cascade
						, FOREIGN KEY (`Fleet_CountryReg`) references `fleet` (`countryReg`) on delete cascade
						, FOREIGN KEY (`Fleet_NumberReg`) references `fleet` (`numberReg`) on delete cascade
						, unique key(`RouteType`)
						)';
		$dbc->exec($query_str);

		echo 'Таблица route создана!<br>';
	}catch(PDOException $err){
		echo "route ".$err->getMessage();
	}

	// ======== Создание таблицы tickets - билеты пассажиров
	try{
		$query_str = 'create table if not exists tickets (ticketId int unsigned primary key AUTO_INCREMENT
						, serviceClass tinyint unsigned default "0"
						, route_Number smallint unsigned not null
						, Route_Airline_IATA-code char(2) not null
						, OrdersToService_OrderId smallint unsigned default "0"
						, TotalPrice smallint unsigned default "0"
						, SeatNumber char(2) default "A0"
						, GateNumber tinyint default "0"
						, Users_PasswordSeria char(4) default"1234"
						, Users_PasswordNumber char(6) default"123456",
						, FOREIGN KEY (`route_Number`) references `route` (`Number`) on delete cascade
						, FOREIGN KEY (`route_airline_IATA-code`) references `route` (`airline_IATA-code`) on delete cascade
						, FOREIGN KEY (`Fleet_CountryReg`) references `fleet` (`countryReg`) on delete cascade
						, FOREIGN KEY (`Fleet_NumberReg`) references `fleet` (`numberReg`) on delete cascade
						, FOREIGN KEY (`Users_PasswordSeria`) references `users` (`passwordSeria`) on delete cascade
						, FOREIGN KEY (`Users_PasswordNumber`) references `users` (`passwordNumber`) on delete cascade
						)';
		$dbc->exec($query_str);

		echo 'Таблица tickets создана!<br>';
	}catch(PDOException $err){
		echo "tickets ".$err->getMessage();
	}
	// ======== Создание таблицы registrationStands - стоек регистрации
	try{
		$query_str = 'create table if not exists registrationStands (number tinyint unsigned primary key default "0"
		, airlineName varchar(10) not null
		, routeType tinyint default "0")';
		$dbc->exec($query_str);
		echo 'Таблица registrationStands создана!<br>';
	}catch(PDOException $err){
		echo "registrationStands".$err->getMessage();
	}
	


	// ======== Создание таблицы timaTable - расписания рейсов
	try{
		$query_str = 'create table if not exists timeTable (Route_Number smallint unsigned not null
						, Route_Airline_IATA-code char(3) not null
						, RegistrationStart time default "00:00"
						, RegistrationEnd time default "00:00"
						, RegistrationStands_Number tinyint unsigned default"0"
						, LandingStart time default "00:00"
						, LandingEnd time default "00:00"
						, Status tinyint unsigned default "0"
						, Users_PasswordSeria char(4) default"1234"
						, Users_PasswordNumber char(6) default"123456",
						, FOREIGN KEY (`Route_Number`) references `route` (`Number`) on delete cascade,
						, FOREIGN KEY (`Route_Airline_IATA-code`) references `route` (`airline_IATA-code`) on delete cascade
						)';
		$dbc->exec($query_str);

		echo 'Таблица timeTable создана!<br>';
	}catch(PDOException $err){
		echo "timeTable ".$err->getMessage();
	}
}

?>