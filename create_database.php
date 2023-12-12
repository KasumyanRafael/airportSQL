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
		$dbc->exec('drop database if exists univer');
		$dbc->exec('create database univer');
		$dbc->exec('use univer');
	}catch(PDOException $err){
		echo $err->getMessage();
	}

	// ======== Создание таблицы user - пользователи системы
	try{
		$query_str = 'create table if not exists user (id int unsigned primary key auto_increment
						, name char(150) default ""
						, stud_id int unsigned
						, prep_id int unsigned
						, pass char(250) default ""
						, type_user enum("1","2","3","4") default "1"
						, unique key(name), key(type_user))';
		$dbc->exec($query_str);
		echo 'Таблица user создана!<br>';
	}catch(PDOException $err){
		echo "user ".$err->getMessage();
	}
	// ======== Создание таблицы type_struc - типы структурных подразделений
	try{
		$query_str = 'create table if not exists type_struc (id smallint unsigned primary key auto_increment
						, name char(100) default ""
						, unique key(name)
						)';
		$dbc->exec($query_str);

		echo 'Таблица типов подразделений создана!<br>';
	}catch(PDOException $err){
		echo "type_struc ".$err->getMessage();
	}
	
	// ======== Создание таблицы structure - структурные подразделения
	try{
		$query_str = 'create table if not exists structure (id mediumint unsigned primary key auto_increment
						, name char(250) default ""
						, type_id smallint unsigned 
						, parent_id mediumint unsigned
						, key(name), key(type_id), key(parent_id)
						, foreign key (type_id) references type_struc(id)
						)';
		$dbc->exec($query_str);

		
		echo 'Таблица структурных подразделений создана!<br>';
	}catch(PDOException $err){
		echo "structure ".$err->getMessage();
	}
	
	// ======== Создание таблицы disc - дисциплины  
	try{
		$query_str = 'create table if not exists disc (id int unsigned primary key auto_increment
						, name char(250) default ""
						, module_plan char(100) default ""
						, index_plan char(20) default ""
						, kaf_id mediumint unsigned comment "внешний ключ для связи с таблицей structure"
						, plan_id int unsigned comment "Ссылка на учебный план"
						, key(name), key(module_plan), key(index_plan)
						, foreign key (kaf_id) references structure(id)
						)';
		$dbc->exec($query_str);

		echo 'Таблица дисциплин создана!<br>';
	}catch(PDOException $err){
		echo "disc ".$err->getMessage();
	}
	
	// ======== Создание таблицы spec - специальности и направления подготовки
	try{
		$query_str = 'create table if not exists spec (id smallint unsigned primary key auto_increment
						, name char(150) default ""
						, shifr char(25) default "", key(shifr), key(name))';
		$dbc->exec($query_str);
		echo 'Таблица spec создана!<br>';
	}catch(PDOException $err){
		echo "spec ".$err->getMessage();
	}
	
	// ======== Создание таблицы grup - академические группы
	try{
		$query_str = 'create table if not exists grup (id int unsigned primary key auto_increment
						, name char(150) default ""
						, year_start char(4) default "" 
						, spec_id smallint unsigned, key(name), key(spec_id), key(year_start)
						, foreign key (spec_id) references spec(id)
						)';
		$dbc->exec($query_str);

		echo 'Таблица групп создана!<br>';
	}catch(PDOException $err){
		echo "grup ".$err->getMessage();
	}

	// ======== Создание таблицы stud - студенты
	try{
		$query_str = 'create table stud (id int unsigned primary key auto_increment
						, fam char(70) default ""
						, name char(50) default ""
						, otch char(50) default ""
						, dt_r date
						, grup_id int unsigned
						, status enum("0","1") default "0"
						, unique key(fam, name, otch, dt_r)
						, key(fam, name, otch)
						, key(grup_id), key(status), key(dt_r)
						, foreign key (grup_id) references grup(id)
						)';
		$dbc->exec($query_str);
		echo 'Таблица студентов создана!<br>';
	}catch(PDOException $err){
		echo "stud " . $err->getMessage();
	}

		
	// ======== Создание таблицы dolz - перечень должностей
	try{
		$query_str = 'create table if not exists dolz (id smallint unsigned primary key auto_increment
						, name char(100) default ""
						, key(name))';
		$dbc->exec($query_str);
		echo 'Таблица dolz создана!<br>';
	}catch(PDOException $err){
		echo "dolz ".$err->getMessage();
	}	

	
	// ======== Создание таблицы stepen - перечень учёных степеней
	try{
		$query_str = 'create table if not exists stepen (id smallint unsigned primary key auto_increment
						, name char(100) default ""
						, nm char(10) default ""
						, unique key(name), key(nm))';
		$dbc->exec($query_str);
		echo 'Таблица stepen создана!<br>';
	}catch(PDOException $err){
		echo "stepen ".$err->getMessage();
	}	

// ======== Создание таблицы prep - преподаватели
	try{
		$query_str = 'create table if not exists prep (id int unsigned primary key auto_increment
						, fam char(70) default ""
						, name char(50) default ""
						, otch char(50) default ""
						, dt_r date
						, structure_id mediumint unsigned
						, dolz_id smallint unsigned
						, stepen_id smallint unsigned
						, unique key(fam, name, otch, dt_r)
						, key(fam, name, otch)
						, key(dolz_id), key(stepen_id), key(dt_r), key(structure_id)
						, foreign key (structure_id) references structure(id)
						, foreign key (dolz_id) references dolz(id)
						, foreign key (stepen_id) references stepen(id)
						)';
		$dbc->exec($query_str);

		echo 'Таблица prep создана!<br>';
	}catch(PDOException $err){
		echo "prep ".$err->getMessage();
	}
}

function journ_tables(){
	/*
		Создание системы журналирования операций с базой данных
	*/
	global $dbc;
	$dbc->exec('use univer');
	
	// ======== Создание основной журнальной таблицы journal - журнал операций
	try{
		$query_str = 'create table journal (id bigint unsigned auto_increment primary key
						, tbl_name char(70) default ""
						, oper char(50) default ""
						, dt timestamp default CURRENT_TIMESTAMP
						, user char(150) default ""
						, key(tbl_name)
						, key(oper)
						, key(dt)
						, key(user)
						)';
		$dbc->exec($query_str);
		echo 'Журнальная таблица создана!<br>';
	}catch(PDOException $err){
		echo "journal " . $err->getMessage();
	}	
	
	// ======== Создание журнальной таблицы stud_journ - студенты
	try{
		$query_str = 'create table stud_journ (id int unsigned
						, fam char(70) default ""
						, name char(50) default ""
						, otch char(50) default ""
						, dt_r date
						, grup_id int unsigned
						, status enum("0","1") default "0"
						, journal_id bigint unsigned
						, key(fam, name, otch, dt_r)
						, key(fam, name, otch)
						, key(grup_id), key(status), key(dt_r)
						)';
		$dbc->exec($query_str);
		echo 'Журнальная таблица студентов создана!<br>';
	}catch(PDOException $err){
		echo "stud_journ " . $err->getMessage();
	}
	
	// ======== Создание триггера добавления в таблицу stud - студенты
	try{
		$query_str = 'create trigger ins_stud after insert on stud
						for each row
							begin
								insert into journal set tbl_name="stud", oper="insert", 
										user = user();
								select last_insert_id() into @last;
								insert into stud_journ set id = NEW.id
									, fam = NEW.fam
									, name = NEW.name
									, otch = NEW.otch
									, dt_r = NEW.dt_r
									, grup_id = NEW.grup_id
									, status = NEW.status
									, journal_id = @last;
							end;
						';
		$dbc->exec($query_str);
		echo 'Триггер добавления для таблицы студентов создан!<br>';
	}catch(PDOException $err){
		echo "ins_stud " . $err->getMessage();
	}
	// ======== Создание триггера удаления из таблицы stud - студенты
	try{
		$query_str = 'create trigger del_stud after delete on stud
						for each row
							begin
								insert into journal set tbl_name="stud", oper="delete", 
										user = user();
								select last_insert_id() into @last;
								insert into stud_journ set id = OLD.id
									, fam = OLD.fam
									, name = OLD.name
									, otch = OLD.otch
									, dt_r = OLD.dt_r
									, grup_id = OLD.grup_id
									, status = OLD.status
									, journal_id = @last;
							end;
						';
		$dbc->exec($query_str);
		echo 'Триггер удаления для таблицы студентов создан!<br>';
	}catch(PDOException $err){
		echo "del_stud " . $err->getMessage();
	}	
	// ======== Создание триггера изменения таблицы stud - студенты
	try{
		$query_str = 'create trigger update_stud after update on stud
						for each row
							begin
								insert into journal set tbl_name="stud", oper="update", 
										user = user();
								select last_insert_id() into @last;
								insert into stud_journ set id = NEW.id
									, fam = NEW.fam
									, name = NEW.name
									, otch = NEW.otch
									, dt_r = NEW.dt_r
									, grup_id = NEW.grup_id
									, status = NEW.status
									, journal_id = @last;
							end;
						';
		$dbc->exec($query_str);
		echo 'Триггер изменения для таблицы студентов создан!<br>';
	}catch(PDOException $err){
		echo "update_stud " . $err->getMessage();
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