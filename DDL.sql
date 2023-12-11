DROP database IF EXISTS `airport`;
CREATE DATABASE `airport`;
use `airport`;
CREATE TABLE `users` (
  `id` int unsigned AUTO_INCREMENT,
  `passwordSeria` char(4) NOT NULL,
  `passwordNumber` char(6) NOT NULL,
  `name` varchar(16) NOT NULL,
  `surname` varchar(16) NOT NULL,
  `birthdayDate` date NOT NULL,
  `password` char(20) default"passwordNumber", 
  `gender` tinyint NOT NULL,
  `userType` enum (‘1’,’2’,’3’,’4’,’5’,’6’) default"1",
  `userAddAccess` enum (‘0’,’1’) default"0",
  PRIMARY KEY (`id`),
  PRIMARY KEY (`passwordSeria`),
  PRIMARY KEY (`passwordNumber`)
  key(`userType`), 
  key(`gender`),
  key(`userAddAccess`)
);
CREATE TABLE `usersContacts` (
  `users_passwordSeria` char(4) NOT NULL,
  `users_passwordNumber` char(6) NOT NULL,
  `email` varchar(20) NOT NULL,
  `phoneNumber` varchar(16) NOT NULL,
  FOREIGN KEY (`users_passwordSeria`) references `users` (`passwordSeria`) on delete cascade,
  FOREIGN KEY (`users_passwordNumber`) references `users` (`passwordNumber`) on delete cascade
);
CREATE TABLE `services` (
  `idServices` int unsigned AUTO_INCREMENT,
  `serviceName` varchar(20) NOT NULL,
  `price_One` int unsigned NOT NULL,
  `measureUnit` varchar(1) NOT NULL,
  PRIMARY KEY (`idServices`),
  UNIQUE KEY(`serviceName`),
  key(`price_One`)
);
CREATE TABLE `airline`(
    `IATA-code` char(2) not null,
    `airlineName` varchar(10) not null,
    primary key(`IATA-code`),
    unique key(`airlineName`)
);
CREATE TABLE `aircrafts`(
    `idAircrafts` int unsigned AUTO_INCREMENT,
    `modelName` varchar(10) not null,
    `averageRange` int unsigned not null,
    primary key(`idAircrafts`),
    unique key(`modelName`),
    key(`AverageRange`)
);
CREATE TABLE `fleet`(
    `countryReg` char(2) not null,
    `numberReg` varchar(6) not null,
    `airline_IATA-code` char(2) not null,
    `aircrafts_idAircrafts` int unsigned not null,
    `businessSeats` smallint unsigned default"0",
    `economySeats` smallint unsigned default"0",
    `cargoSlots` smallint unsigned default"0",
    `madeOn` date not null,
    primary key(`countryReg`),
    primary key(`numberReg`),
    FOREIGN KEY (`airline_IATA-code`) references `airline` (`IATA-code`) on delete cascade,
    FOREIGN KEY (`aircrafts_idAircrafts`) references `aircrafts` (`idAircrafts`) on delete cascade
);
create table `ordersToService`(
    `orderId` int unsigned AUTO_INCREMENT,
    `services_idServices` tinyint unsigned not null,
    `amount` tinyint unsigned default "1",
    `users_passwordSeria` char(4) not null,
    `users_passwordNumber` char(6) not null,
    primary key(`orderId`),
    FOREIGN KEY (`users_passwordSeria`) references `users` (`passwordSeria`) on delete cascade,
    FOREIGN KEY (`users_passwordNumber`) references `users` (`passwordNumber`) on delete cascade,
    FOREIGN KEY (`services_idServices`) references `services` (`idServices`) on delete cascade, 
);
create table `destinations`(
    `IATA-dest` char(3) not null,
    `cityName` varchar(18) default"Владикавказ(Беслан)";
    `country` varchar(30) default"Россия";
    primary key(`IATA-dest`),
    unique key(`cityName`)
);
create table `route`(
    `airline_IATA-code` char(2) not null,
    `number` int unsigned not null,
    `destinations_IATA-dest` char(3) not null,
    `RouteType` tinyint unsigned default "1",
    `ArrTime` time not null,
    `DepTime` time not null,
    `FlightDays` varchar(7) default"1234567",
    `Fleet_CountryReg` char(2) not null,
    `Fleet_NumberReg` varchar(6) not null,
    `FirstFlight` date not null,
    `LastFlight` date not null,
    `AverageRouteDistance` int unsigned not null,
    `AverageDuration` time not null,
    `EconomyPrice` smallint unsigned default "0",
    `BusinessPrice` smallint unsigned default "0",
    `CargoPrice` smallint unsigned default "0",
    primary key(`airline_IATA-code`),
    primary key(`number`),
    FOREIGN KEY (`airline_IATA-code`) references `airline` (`IATA-code`) on delete cascade,
    FOREIGN KEY (`destinations_IATA-dest`) references `destination` (`IATA-dest`) on delete cascade,
    FOREIGN KEY (`Fleet_CountryReg`) references `fleet` (`countryReg`) on delete cascade,
    FOREIGN KEY (`Fleet_NumberReg`) references `fleet` (`numberReg`) on delete cascade,
    unique key(`airline_IATA-code`),
    unique key(`number`)
);
create table `tickets`(
    `ticketId` int unsigned AUTO_INCREMENT,
    `serviceClass` tinyint unsigned default "0",
    `route_Number` smallint unsigned not null,
    `Route_Airline_IATA-code` char(3) not null,
    `OrdersToService_OrderId` smallint unsigned default "0",
    `TotalPrice` smallint unsigned default "0",
    `SeatNumber` char(2) default "A0",
    `GateNumber` tinyint default "0",
    `Users_PasswordSeria` char(4) default"1234",
    `Users_PasswordNumber` char(6) default"123456",
    primary key(`ticketId`),
    FOREIGN KEY (`route_Number`) references `route` (`Number`) on delete cascade,
    FOREIGN KEY (`route_airline_IATA-code`) references `route` (`airline_IATA-code`) on delete cascade,
    FOREIGN KEY (`Fleet_CountryReg`) references `fleet` (`countryReg`) on delete cascade,
    FOREIGN KEY (`Fleet_NumberReg`) references `fleet` (`numberReg`) on delete cascade,
    FOREIGN KEY (`Users_PasswordSeria`) references `users` (`passwordSeria`) on delete cascade,
    FOREIGN KEY (`Users_PasswordNumber`) references `users` (`passwordNumber`) on delete cascade
);
create table `registrationStands`(
    `number` tinyint unsigned default "0",
    `routeType` tinyint default "0",
    primary key(`number`)
);
create table `timeTable`(
    `Route_Number` smallint unsigned not null,
    `Route_Airline_IATA-code` char(3) not null,
    `RegistrationStart` time default "00:00",
    `RegistrationEnd` time default "00:00",
    `RegistrationStands_Number` tinyint unsigned default"0",
    `LandingStart` time default "00:00",
    `LandingEnd` time default "00:00",
    `Status` tinyint unsigned default "0"
);