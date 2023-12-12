<?php
// Модуль создания таблиц БД create_database.php
include('con_db.php');

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





function insert_test_data(){
	/*
		Функция наполнения тестовыми данными таблиц БД univer
	*/
	global $dbc;
	$stud_count = 100;	// количество тестовых записей в таблице stud
	$prep_count = 20;	// количество тестовых записей в таблице prep

	//============ Пользователи
	// генерирование пароля
	$pass = password_hash("1", PASSWORD_DEFAULT);
	$insert_str = "insert into user (name, pass, type_user) values (:name,:pass, :type)";
	// подготовка запроса
	try{
		$sth = $dbc->prepare($insert_str);
		// отправка запроса с данными
		for ($i=1; $i < 5; $i++){
			$name = 'user'.$i;
			$type = $i;
			echo $type;
			$sth->execute(array(':name'=>$name, ':pass'=>$pass, ':type'=>$type));
		}
		echo 'insert user - OK!<br>';
	}catch(PDOException $err){
		echo 'insert user ' . $err->getMessage();
	}

	//============ Типы структурных подразделений
	try{
		$insert_str = 'insert into type_struc (name) values 
				("Университет"),
				("Факультет"),
				("Кафедра"),
				("Управление"),
				("Отдел"), ("Лаборатория"),("Центр")
				';
		$dbc->exec($insert_str);
		echo 'insert type_struc - OK!<br>';
	}catch(PDOException $err){
		echo 'insert type_struc ' . $err->getMessage();
	}
	
	//============ Структурные подразделения
	try{
		$insert_str = 'insert into structure (name, type_id, parent_id) values 
					("СОГУ",1,0)
					';
		$dbc->exec($insert_str);
		$dbc->exec("update structure set id = 0 where id = 1");
		$dbc->exec("alter table structure add foreign key(parent_id) references structure(id)");
		$dbc->exec("alter table structure auto_increment = 1");
		
		$insert_str = 'insert into structure (name, type_id, parent_id) values 
					("Математики и компьютерных наук",2,0),
					("прикладной математики и информатики",3,1),
					("алгебры и анализа",3,1)';
					
		$struc_count = $dbc->exec($insert_str);
		echo 'insert structure - OK!<br>';
	}catch(PDOException $err){
		echo 'insert structure ' . $err->getMessage();
	}

	//============ Дисциплины
	try{
		$insert_str = 'insert into disc (name, kaf_id) values 
					("Базы данных",2 ),
					("Алгебра",3),
					("Функциональный анализ", 3),
					("Теория операторов", 3),
					("Анализ данных", 2), ("Мобильная разработка", 2),("Современные языки программирования",2)
					';
		$dbc->exec($insert_str);
		echo 'insert disc - OK!<br>';
	}catch(PDOException $err){
		echo 'insert disc ' . $err->getMessage();
	}

	//============ Специальности
	try{
		$insert_str = 'insert into spec (name, shifr) values 
				("Прикладная математика","01.03.02"),
				("ИВТ","09.03.01"),
				("Педобразование","44.03.05"),
				("Математика","01.01.01")
				';
		$dbc->exec($insert_str);
		echo 'insert spec - OK!<br>';
	}catch(PDOException $err){
		echo 'insert spec ' . $err->getMessage();
	}

	//============ Группы
	try{
		$insert_str = 'insert into grup (name, spec_id, year_start) values 
				("31",1,"2020"),
				("21",1,"2021"),
				("22",1,"2021"),
				("33",2,"2020"), ("34",3,"2020"),("14",3,"2022")
				';
		$group_count = $dbc->exec($insert_str);
		echo 'insert grup - OK!<br>';
	}catch(PDOException $err){
		echo 'insert grup ' . $err->getMessage();
	}


	//============ Студенты
	
	try{
		$insert_str = 'insert into stud (fam, name, otch, dt_r, grup_id, status) values 
					 (:fam, :name, :otch, :dt_r,:grup_id, :status)
					';
		$sth = $dbc->prepare($insert_str);
		while ($stud_count > 0){
			$gender = (rand(0,1) == 1) ? 'f':'m';
			$rdate_r = (string)rand(1987,2005) . '-' 
						. str_pad((string)rand(1,12),2,'0',STR_PAD_LEFT) . '-' 
						. str_pad((string)rand(1,30),2,'0',STR_PAD_LEFT);    
			$rgrup_id = rand(1,$group_count);
			$d = array(':fam'=>rand_fam($gender), ':name'=>rand_name($gender), ':otch'=>rand_otch($gender),
						':dt_r'=>$rdate_r, ':grup_id'=>$rgrup_id, ':status'=>'0');
			$sth->execute($d);
			$stud_count--;
		}
		echo 'insert stud - OK!<br>';		
	}catch(PDOException $err){
		echo 'insert stud ' . $err->getMessage();
	}

	//============ Должности
	try{
		$insert_str = 'insert into dolz (name) values 
				 ("ассистент")
				,("старший преподаватель")
				,("доцент")
				,("профессор")
				,("учебный мастер")
				,("декан")
				,("заведующий кафедрой")
				,("лаборант")
				,("заведующий лабораторией")
				,("заместитель декана")
				';
		$dolz_count = $dbc->exec($insert_str);
		echo 'insert dolz - OK!<br>';
	}catch(PDOException $err){
		echo 'insert dolz ' . $err->getMessage();
	}

	//============ Ученые степени
	try{
		$insert_str = 'insert into stepen (name, nm) values 
				 ("без степени","бс")
				,("кандидат физико-математических наук","к.ф.-м.н.")
				,("доктор физико-математических наук","д.ф.-м.н.")
				,("кандидат педагогических наук","к.п.н.")
				,("доктор педагогических наук","д.п.н.")
				,("кандидат технических наук","к.т.н.")
				,("доктор технических наук","д.т.н.")
				';
		$stepen_count = $dbc->exec($insert_str);
		echo 'insert stepen - OK!<br>';		
	}catch(PDOException $err){
		echo 'insert stepen ' . $err->getMessage();
	}

	//============ Преподаватели
	try{
		$insert_str = 'insert into prep (fam, name, otch, dt_r, structure_id, dolz_id, stepen_id) values 
					 (:fam, :name, :otch, :dt_r,:structure_id, :dolz_id, :stepen_id)
					';
		$sth = $dbc->prepare($insert_str);
		while ($prep_count > 0){
			$gender = (rand(0,1) == 1) ? 'f':'m';
			$rdate_r = (string)rand(1957,2000) . '-' 
						. str_pad((string)rand(1,12),2,'0',STR_PAD_LEFT) . '-' 
						. str_pad((string)rand(1,28),2,'0',STR_PAD_LEFT);    
			
			$rstruc_id = rand(1,$struc_count);
			$rdolz_id = rand(1,$dolz_count);
			$rstepen_id = rand(1,$stepen_count);
			
			
			$d = array(':fam'=>rand_fam($gender), ':name'=>rand_name($gender), ':otch'=>rand_otch($gender)
						, ':dt_r'=>$rdate_r, ':structure_id'=>$rstruc_id, ':dolz_id'=>$rdolz_id
						, ':stepen_id'=>$rstepen_id );
			
			$sth->execute($d);
			$prep_count--;
		}
		echo 'insert prep - OK!<br>';
	}catch(PDOException $err){
		echo 'insert prep ' . $err->getMessage();
	}
	
}


function rand_fam($fgender){
	/*
		Функция возвращает случайную фамилию из списка наиболее распространенных фамили в России
		Вход: $fgender - строка ("f" - женский | "m" - мужской) - пол фамилии, 
				которую необходимо возвратить
		Выход: строка - фамилия 
	*/
	$list_fam = explode(',','Иванов,Смирнов,Кузнецов,Попов,Васильев,Петров,Соколов,Михайлов,Новиков,Федоров,Морозов,Волков,Алексеев,Лебедев,Семенов,Егоров,Павлов,Козлов,Степанов,Николаев,Орлов,Андреев,Макаров,Никитин,Захаров,Зайцев,Соловьев,Борисов,Яковлев,Григорьев,Романов,Воробьев,Сергеев,Кузьмин,Фролов,Александров,Дмитриев,Королев,Гусев,Киселев,Ильин,Максимов,Поляков,Сорокин,Виноградов,Ковалев,Белов,Медведев,Антонов,Тарасов,Жуков,Баранов,Филиппов,Комаров,Давыдов,Беляев,Герасимов,Богданов,Осипов,Сидоров,Матвеев,Титов,Марков,Миронов,Крылов,Куликов,Карпов,Власов,Мельников,Денисов,Гаврилов,Тихонов,Казаков,Афанасьев,Данилов,Савельев,Тимофеев,Фомин,Чернов,Абрамов,Мартынов,Ефимов,Федотов,Щербаков,Назаров,Калинин,Исаев,Чернышев,Быков,Маслов,Родионов,Коновалов,Лазарев,Воронин,Климов,Филатов,Пономарев,Голубев,Кудрявцев,Прохоров,Наумов,Потапов,Журавлев,Овчинников,Трофимов,Леонов,Соболев,Ермаков,Колесников,Гончаров,Емельянов,Никифоров,Грачев,Котов,Гришин,Ефремов,Архипов,Громов,Кириллов,Малышев,Панов,Моисеев,Румянцев,Акимов,Кондратьев,Бирюков,Горбунов,Анисимов,Еремин,Тихомиров,Галкин,Лукьянов,Михеев,Скворцов,Юдин,Белоусов,Нестеров,Симонов,Прокофьев,Харитонов,Князев,Цветков,Левин,Митрофанов,Воронов,Аксенов,Софронов,Мальцев,Логинов,Горшков,Савин,Краснов,Майоров,Демидов,Елисеев,Рыбаков,Сафонов,Плотников,Демин,Хохлов,Фадеев,Молчанов,Игнатов,Литвинов,Ершов,Ушаков,Дементьев,Рябов,Мухин,Калашников,Леонтьев,Лобанов,Кузин,Корнеев,Евдокимов,Бородин,Платонов,Некрасов,Балашов,Бобров,Жданов,Блинов,Игнатьев,Коротков,Муравьев,Крюков,Беляков,Богомолов,Дроздов,Лавров,Зуев,Петухов,Ларин,Никулин,Серов,Терентьев,Зотов,Устинов,Фокин,Самойлов,Константинов,Сахаров,Шишкин,Самсонов,Черкасов,Чистяков,Носов,Спиридонов,Карасев,Авдеев,Воронцов,Зверев,Владимиров,Селезнев,Нечаев,Кудряшов,Седов,Фирсов,Андрианов,Панин,Головин,Терехов,Ульянов,Шестаков,Агеев,Никонов,Селиванов,Баженов,Гордеев,Кожевников,Пахомов,Зимин,Костин,Широков,Филимонов,Ларионов,Овсянников,Сазонов,Суворов,Нефедов,Корнилов,Любимов,Львов,Горбачев,Копылов,Лукин,Токарев,Кулешов,Шилов,Большаков,Панкратов,Родин,Шаповалов,Покровский,Бочаров,Никольский,Маркин,Горелов,Агафонов,Березин,Ермолаев,Зубков,Куприянов,Трифонов,Масленников,Круглов,Третьяков,Колосов,Рожков,Артамонов,Шмелев,Лаптев,Лапшин,Федосеев,Зиновьев,Зорин,Уткин,Столяров,Зубов,Ткачев,Дорофеев,Антипов,Завьялов,Свиридов,Золотарев,Кулаков,Мещеряков,Макеев,Дьяконов,Гуляев,Петровский,Бондарев,Поздняков,Панфилов,Кочетков,Суханов,Рыжов,Старостин,Калмыков,Колесов,Золотов,Кравцов,Субботин,Шубин,Щукин,Лосев,Винокуров,Лапин,Парфенов,Исаков,Голованов,Коровин,Розанов,Артемов,Козырев,Русаков,Алешин,Крючков,Булгаков,Кошелев,Сычев,Синицын,Черных,Рогов,Кононов,Лаврентьев,Евсеев,Пименов,Пантелеев,Горячев,Аникин,Лопатин,Рудаков,Одинцов,Серебряков,Панков,Дегтярев,Орехов,Царев,Шувалов,Кондрашов,Горюнов,Дубровин,Голиков,Курочкин,Латышев,Севастьянов,Вавилов,Ерофеев,Сальников,Клюев,Носков,Озеров,Кольцов,Комиссаров,Меркулов,Киреев,Хомяков,Булатов,Ананьев,Буров,Шапошников,Дружинин,Островский,Шевелев,Долгов,Суслов,Шевцов,Пастухов,Рубцов,Бычков,Глебов,Ильинский,Успенский,Дьяков,Кочетов,Вишневский,Высоцкий,Глухов,Дубов,Бессонов,Ситников,Астафьев,Мешков,Шаров,Яшин,Козловский,Туманов,Басов,Корчагин,Болдырев,Олейников,Чумаков,Фомичев,Губанов,Дубинин,Шульгин,Касаткин,Пирогов,Семин,Трошин,Горохов,Стариков,Щеглов,Фетисов,Колпаков,Чесноков,Зыков,Верещагин,Минаев,Руднев,Троицкий,Окулов,Ширяев,Малинин,Черепанов,Измайлов,Алехин,Зеленин,Касьянов,Пугачев,Павловский,Чижов,Кондратов,Воронков,Капустин,Сотников,Демьянов,Косарев,Беликов,Сухарев,Белкин,Беспалов,Кулагин,Савицкий,Жаров,Хромов,Еремеев,Карташов,Астахов,Русанов,Сухов,Вешняков,Волошин,Козин,Худяков,Жилин,Малахов,Сизов,Ежов,Толкачев,Анохин,Вдовин,Бабушкин,Усов,Лыков,Горлов,Коршунов,Маркелов,Постников,Черный,Дорохов,Свешников,Гущин,Калугин,Блохин,Сурков,Кочергин,Греков,Казанцев,Швецов,Ермилов,Парамонов,Агапов,Минин,Корнев,Черняев,Гуров,Ермолов,Сомов,Добрынин,Барсуков,Глушков,Чеботарев,Москвин,Уваров,Безруков,Муратов,Раков,Снегирев,Гладков,Злобин,Моргунов,Поликарпов,Рябинин,Судаков,Кукушкин,Калачев,Грибов,Елизаров,Звягинцев,Корольков,Федосов');
	$rand_count = count($list_fam);

	$ret_val = $list_fam[rand(0,$rand_count-1)];
	if ($fgender == 'f'){
		if(substr($ret_val,-1)=='й'){
			$ret_val = substr($ret_val,0,-2) . 'ая';
		}else{
			$ret_val = $ret_val . 'а';
		}
	}
	return $ret_val;
}
function rand_name($fgender){
	/*
		Функция возвращает случайное имя из списка наиболее распространенных имен в России
		Вход: fgender - строка ("f" - женский | "m" - мужской) - пол имени, 
				которое необходимо возвратить
		Выход: строка - имя 
	*/
	$list_name_m = explode(',','Александр,Михаил,Кирилл,Алексей,Даниил,Иван,Артем,Владимир,Матвей,Денис,Дмитрий,Григорий,Виктор,Тимофей,Егор,Степан,Ярослав,Арсений,Илья,Максим,Роман,Никита,Василий,Тимур,Платон,Андрей,Дамир,Мирон,Сергей,Евгений,Лев,Николай,Богдан,Эрик,Федор,Назар,Захар,Глеб,Демьян,Георгий,Савелий,Эльдар,Игорь,Мирослав,Руслан,Макар,Яков,Марк,Станислав,Владислав');
	$list_name_f = explode(',','Анна,Софья,Алиса,Мария,Анастасия,Вероника,Виктория,Маргарита,Василиса,Кира,Ксения,Дарья,Валерия,Елена,Таисия,Екатерина,Александра,Милана,Вера,Татьяна,Ника,Варвара,Арина,Любовь,Агата,Полина,Олеся,Эвелина,Ева,Ярослава,Диана,Кристина,Амалия,Алина,Злата,Евгения,Камилла,Мирослава,Глафира,Гюзель,Лаура,Майя,Наталья,Виталина,Елизавета,Эльмира,Лиана,Эмилия,Марианна,Дарина');
	
	if ($fgender == 'f'){
		$rand_count_f = count($list_name_f);
		$ret_val = $list_name_f[rand(0,$rand_count_f -1)];
	}else{
		$rand_count_m = count($list_name_m);
		$ret_val = $list_name_m[rand(0,$rand_count_m -1)];
	}
	return $ret_val;
}
function rand_otch($fgender){
	/*
		Функция возвращает случайное отчество на основе списка наиболее распространенных мужских 
		имен в России
		Вход: fgender - строка ("f" - женский | "m" - мужской) - пол отчества, 
				которое необходимо возвратить
		Выход: строка - отчество
	*/
	
	$list_otch_f = explode(',','Александр,Михаил,Кирилл,Алексей,Даниил,Иван,Артем,Владимир,Матвей,Денис,Дмитриевна,Григорьевна,Виктор,Тимофей,Егор,Степан,Ярослав,Максим,Роман,Васильевна,Тимур,Платон,Андрей,Дамир,Мирон,Сергей,Евгеньевна,Львовна,Николаевна,Богдан,Эрик,Федор,Назар,Захар,Глеб,Демьян,Георгиевна,Савельевна,Эльдар,Игоревна,Мирослав,Руслан,Макар,Яковлевна,Марк,Станислав,Владислав');
	$list_otch_m = explode(',','Александр,Михаил,Кирилл,Алексей,Даниил,Иван,Артем,Владимир,Матвей,Денис,Дмитриевич,Григорьевич,Виктор,Тимофей,Егор,Степан,Ярослав,Арсеньевич,Максим,Роман,Васильевич,Тимур,Платон,Андрей,Дамир,Мирон,Сергей,Евгеньевич,Львович,Николаевич,Богдан,Эрик,Федор,Назар,Захар,Глеб,Демьян,Георгиевич,Савельевич,Эльдар,Игоревич,Мирослав,Руслан,Макар,Яковлевич,Марк,Станислав,Владислав');	
	$rand_count_f = count($list_otch_f);
	$rand_count_m = count($list_otch_m);
	
	if($fgender == 'f'){
		$ret_val = $list_otch_f[rand(0, $rand_count_f-1)];
		//echo substr($ret_val,-2);
		if (substr($ret_val,-4)=='ей'){
			$ret_val = substr($ret_val,0,-2) . 'евна';
		}else if(substr($ret_val,-2)=='а') {
			$ret_val = $ret_val;
		}else{
			$ret_val = $ret_val . 'овна';
		}
	}else{
		$ret_val = $list_otch_m[rand(0, $rand_count_m-1)];
		if (substr($ret_val,-4)=='ей'){
			$ret_val = substr($ret_val,0,-2) . 'евич';
		}else if(substr($ret_val,-2)=='ч') {
			$ret_val = $ret_val;
		}else{
			$ret_val = $ret_val . 'ович';
		}
	}
	return $ret_val;
}

function rand_fio($fgender){
	/*
		Функция возвращает случайные фамилию, имя и отчество из списка наиболее 
		распространенных фамилий и имен в России
		Вход: fgender - строка ("f" - женский | "m" - мужской) - пол 
		Выход: массив строк - ['фамилия', 'имя', 'отчество']
	*/
	$ret_val = explode(',', rand_fam(fgender) . ',' . rand_name(fgender) . ',' . rand_otch(fgender));
	return $ret_val;
}

create_database();
journ_tables();
insert_test_data();

?>