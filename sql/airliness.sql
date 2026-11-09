SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `uemail` varchar(100) NOT NULL,
  `upassword` varchar(255) NOT NULL,
  `uphone` varchar(20) NOT NULL,
  `ucity` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `flights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flight_no` varchar(10) NOT NULL,
  `origin` varchar(50) NOT NULL,
  `destination` varchar(50) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `seats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flight_id` int(11) NOT NULL,
  `seat_number` varchar(5) NOT NULL,
  `seat_class` varchar(20) NOT NULL DEFAULT 'Economy',
  `is_booked` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `flight_id` (`flight_id`),
  CONSTRAINT `fk_seats_flight` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pnr` varchar(10) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `passenger_name` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'CONFIRMED',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pnr` (`pnr`),
  KEY `flight_id` (`flight_id`),
  KEY
