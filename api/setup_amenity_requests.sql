-- Run this SQL script in your database to create the amenity requests table
-- You can run this in phpMyAdmin or any MySQL client

USE demiren_v1;

CREATE TABLE IF NOT EXISTS `tbl_customer_amenity_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `booking_room_id` int(11) NOT NULL,
  `charges_master_id` int(11) NOT NULL,
  `request_quantity` int(11) NOT NULL DEFAULT 1,
  `request_price` decimal(10,2) NOT NULL,
  `request_total` decimal(10,2) NOT NULL,
  `request_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_notes` text DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `requested_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` datetime DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  PRIMARY KEY (`request_id`),
  KEY `fk_amenity_request_booking` (`booking_id`),
  KEY `fk_amenity_request_room` (`booking_room_id`),
  KEY `fk_amenity_request_charges` (`charges_master_id`),
  KEY `fk_amenity_request_employee` (`processed_by`),
  CONSTRAINT `fk_amenity_request_booking` FOREIGN KEY (`booking_id`) REFERENCES `tbl_booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_amenity_request_room` FOREIGN KEY (`booking_room_id`) REFERENCES `tbl_booking_room` (`booking_room_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_amenity_request_charges` FOREIGN KEY (`charges_master_id`) REFERENCES `tbl_charges_master` (`charges_master_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_amenity_request_employee` FOREIGN KEY (`processed_by`) REFERENCES `tbl_employee` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert some sample data for testing (optional)
INSERT INTO `tbl_customer_amenity_requests` 
(`booking_id`, `booking_room_id`, `charges_master_id`, `request_quantity`, `request_price`, `request_total`, `request_status`, `customer_notes`, `requested_at`) 
VALUES 
(1, 1, 1, 2, 0.00, 0.00, 'pending', 'Please deliver extra towels to room', NOW()),
(1, 2, 2, 1, 400.00, 400.00, 'pending', 'Need an extra bed for guest', NOW());

-- Verify the table was created
SELECT * FROM `tbl_customer_amenity_requests`;
