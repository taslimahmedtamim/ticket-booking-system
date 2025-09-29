-- Tables + sample data for Bus Ticket Booking
CREATE TABLE IF NOT EXISTS routes (
  route_id INT AUTO_INCREMENT PRIMARY KEY,
  from_city VARCHAR(100) NOT NULL,
  to_city   VARCHAR(100) NOT NULL,
  distance_km INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS buses (
  bus_id INT AUTO_INCREMENT PRIMARY KEY,
  bus_no VARCHAR(20) NOT NULL UNIQUE,
  model  VARCHAR(50),
  seats_total INT NOT NULL CHECK (seats_total > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS trips (
  trip_id INT AUTO_INCREMENT PRIMARY KEY,
  route_id INT NOT NULL,
  bus_id INT NOT NULL,
  trip_date DATE NOT NULL,
  depart_time TIME NOT NULL,
  arrive_time TIME NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE CASCADE,
  FOREIGN KEY (bus_id)   REFERENCES buses(bus_id)  ON DELETE CASCADE,
  INDEX(route_id), INDEX(bus_id), INDEX(trip_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  trip_id INT NOT NULL,
  passenger_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  seat_no INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (trip_id) REFERENCES trips(trip_id) ON DELETE CASCADE,
  CONSTRAINT uniq_trip_seat UNIQUE (trip_id, seat_no)
) ENGINE=InnoDB;

INSERT INTO routes (from_city, to_city, distance_km) VALUES
('Dhaka', 'Chittagong', 250),
('Dhaka', 'Khulna', 260),
('Chittagong', 'Sylhet', 320);

INSERT INTO buses (bus_no, model, seats_total) VALUES
('B-101', 'Hino AK1J', 40),
('B-202', 'Volvo 9700', 36),
('B-303', 'Scania K410', 44);

INSERT INTO trips (route_id, bus_id, trip_date, depart_time, arrive_time, price) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '08:00:00', '13:00:00', 800.00),
(1, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', '20:00:00', 900.00),
(2, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '07:30:00', '13:30:00', 850.00),
(3, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '06:45:00', '12:30:00', 950.00);