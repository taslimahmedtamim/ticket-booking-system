-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.byetcluster.com
-- Generation Time: Aug 21, 2025 at 09:38 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `passenger_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `seat_no` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `buses` (
  `bus_id` int(11) NOT NULL,
  `bus_no` varchar(20) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `seats_total` int(11) NOT NULL
) ;


INSERT INTO `buses` (`bus_id`, `bus_no`, `model`, `seats_total`) VALUES
(1, 'B-101', 'Hino AK1J', 40),
(2, 'B-202', 'Volvo 9700', 36),
(3, 'B-303', 'Scania K410', 44);


CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `from_city` varchar(100) NOT NULL,
  `to_city` varchar(100) NOT NULL,
  `distance_km` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


INSERT INTO `routes` (`route_id`, `from_city`, `to_city`, `distance_km`) VALUES
(1, 'Dhaka', 'Chittagong', 250),
(2, 'Dhaka', 'Khulna', 260),
(3, 'Chittagong', 'Sylhet', 320);



CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `trip_date` date NOT NULL,
  `depart_time` time NOT NULL,
  `arrive_time` time NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



INSERT INTO `trips` (`trip_id`, `route_id`, `bus_id`, `trip_date`, `depart_time`, `arrive_time`, `price`) VALUES
(1, 1, 1, '2025-08-22', '08:00:00', '13:00:00', '800.00'),
(2, 1, 2, '2025-08-22', '15:00:00', '20:00:00', '900.00'),
(3, 2, 3, '2025-08-23', '07:30:00', '13:30:00', '850.00'),
(4, 3, 2, '2025-08-24', '06:45:00', '12:30:00', '950.00');


ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `uniq_trip_seat` (`trip_id`,`seat_no`);


ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);


ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `trip_date` (`trip_date`);


ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `buses`
  MODIFY `bus_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE CASCADE;


ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`bus_id`) ON DELETE CASCADE;
COMMIT;

