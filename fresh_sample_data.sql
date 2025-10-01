-- Fresh Sample Data for Testing Calendar Filtering
-- This script provides comprehensive sample data for all booking-related tables

-- 0. TBL_CUSTOMERS_WALK_IN - Walk-in customers (needed for bookings)
INSERT INTO `tbl_customers_walk_in` (`customers_walk_in_fname`, `customers_walk_in_lname`, `customers_walk_in_phone`, `customers_walk_in_email`, `customers_walk_in_address`, `customers_walk_in_created_at`) VALUES
('John', 'Smith', '09123456789', 'john.smith@email.com', '123 Main St, City', '2024-12-01 09:00:00'),
('Maria', 'Garcia', '09123456790', 'maria.garcia@email.com', '456 Oak Ave, City', '2024-12-02 10:00:00'),
('David', 'Johnson', '09123456791', 'david.johnson@email.com', '789 Pine St, City', '2024-12-03 11:00:00'),
('Sarah', 'Wilson', '09123456792', 'sarah.wilson@email.com', '321 Elm St, City', '2024-12-04 12:00:00'),
('Michael', 'Brown', '09123456793', 'michael.brown@email.com', '654 Maple Ave, City', '2024-12-05 13:00:00'),
('Lisa', 'Davis', '09123456794', 'lisa.davis@email.com', '987 Cedar St, City', '2024-12-06 14:00:00'),
('Robert', 'Miller', '09123456795', 'robert.miller@email.com', '147 Birch St, City', '2024-12-07 15:00:00'),
('Jennifer', 'Moore', '09123456796', 'jennifer.moore@email.com', '258 Spruce Ave, City', '2024-12-08 16:00:00'),
('William', 'Taylor', '09123456797', 'william.taylor@email.com', '369 Walnut St, City', '2024-12-09 17:00:00'),
('Amanda', 'Anderson', '09123456798', 'amanda.anderson@email.com', '741 Cherry Ave, City', '2024-12-10 18:00:00'),
('Christopher', 'Thomas', '09123456799', 'christopher.thomas@email.com', '852 Ash St, City', '2024-12-11 19:00:00'),
('Jessica', 'Jackson', '09123456800', 'jessica.jackson@email.com', '963 Poplar Ave, City', '2024-12-12 20:00:00');

-- 1. TBL_BOOKING - Main booking records
-- Using only walk-in customers to avoid foreign key constraints
INSERT INTO `tbl_booking` (`customers_id`, `customers_walk_in_id`, `guests_amnt`, `booking_totalAmount`, `booking_downpayment`, `reference_no`, `booking_checkin_dateandtime`, `booking_checkout_dateandtime`, `booking_created_at`, `booking_isArchive`) VALUES
(NULL, 1, 1, 2500, 500, 'REF-2024-001', '2024-12-15 14:00:00', '2024-12-17 11:00:00', '2024-12-01 10:30:00', 0),
(NULL, 2, 2, 1800, 300, 'REF-2024-002', '2024-12-18 15:00:00', '2024-12-20 12:00:00', '2024-12-02 14:20:00', 0),
(NULL, 3, 3, 3200, 600, 'REF-2024-003', '2024-12-22 16:00:00', '2024-12-24 10:00:00', '2024-12-03 09:15:00', 0),
(NULL, 4, 3, 2100, 400, 'REF-2024-004', '2024-12-25 13:00:00', '2024-12-27 11:00:00', '2024-12-04 16:45:00', 0),
(NULL, 5, 5, 2800, 500, 'REF-2024-005', '2024-12-28 14:30:00', '2024-12-30 12:00:00', '2024-12-05 11:30:00', 0),
(NULL, 6, 7, 1900, 300, 'REF-2024-006', '2024-12-31 15:00:00', '2025-01-02 10:00:00', '2024-12-06 13:20:00', 0),
(NULL, 7, 1, 2400, 400, 'REF-2025-001', '2025-01-03 16:00:00', '2025-01-05 11:00:00', '2024-12-07 08:45:00', 0),
(NULL, 8, 2, 1700, 300, 'REF-2025-002', '2025-01-06 14:00:00', '2025-01-08 12:00:00', '2024-12-08 15:10:00', 0),
(NULL, 9, 3, 2600, 500, 'REF-2025-003', '2025-01-09 15:30:00', '2025-01-11 10:00:00', '2024-12-09 12:30:00', 0),
(NULL, 10, 3, 2200, 400, 'REF-2025-004', '2025-01-12 13:00:00', '2025-01-14 11:00:00', '2024-12-10 14:15:00', 0),
(NULL, 11, 5, 3000, 600, 'REF-2025-005', '2025-01-15 16:00:00', '2025-01-17 12:00:00', '2024-12-11 10:45:00', 0),
(NULL, 12, 7, 1850, 300, 'REF-2025-006', '2025-01-18 14:30:00', '2025-01-20 10:00:00', '2024-12-12 16:20:00', 0),
(NULL, 1, 1, 2700, 500, 'REF-2025-007', '2025-01-21 15:00:00', '2025-01-23 11:00:00', '2024-12-13 09:30:00', 0),
(NULL, 2, 2, 1950, 300, 'REF-2025-008', '2025-01-24 16:30:00', '2025-01-26 12:00:00', '2024-12-14 13:45:00', 0),
(NULL, 3, 3, 2300, 400, 'REF-2025-009', '2025-01-27 14:00:00', '2025-01-29 10:00:00', '2024-12-15 11:20:00', 0);

-- 2. TBL_BOOKING_ROOM - Room assignments for bookings
INSERT INTO `tbl_booking_room` (`booking_id`, `roomtype_id`, `roomnumber_id`, `bookingRoom_adult`, `bookingRoom_children`) VALUES
-- Booking 1: Single Room
(1, 1, 1, 1, 0),
-- Booking 2: Double Room
(2, 2, 2, 2, 0),
-- Booking 3: Standard Twin Room
(3, 3, 3, 2, 1),
-- Booking 4: Triple Room
(4, 4, 4, 3, 0),
-- Booking 5: Quadruple Room
(5, 5, 5, 4, 1),
-- Booking 6: Family Room A
(6, 6, 6, 5, 2),
-- Booking 7: Single Room
(7, 1, 7, 1, 0),
-- Booking 8: Double Room
(8, 2, 8, 2, 0),
-- Booking 9: Standard Twin Room
(9, 3, 9, 2, 1),
-- Booking 10: Triple Room
(10, 4, 10, 3, 0),
-- Booking 11: Quadruple Room
(11, 5, 11, 4, 1),
-- Booking 12: Family Room A
(12, 6, 12, 5, 2),
-- Booking 13: Single Room
(13, 1, 13, 1, 0),
-- Booking 14: Double Room
(14, 2, 14, 2, 0),
-- Booking 15: Standard Twin Room
(15, 3, 15, 2, 1);

-- 3. TBL_BILLING - Billing records
INSERT INTO `tbl_billing` (`booking_id`, `billing_dateandtime`, `billing_invoice_number`, `billing_downpayment`, `billing_vat`, `billing_total_amount`, `billing_balance`) VALUES
(1, '2024-12-01 10:35:00', 'INV-2024-001', 500, 250, 2500, 0),
(2, '2024-12-02 14:25:00', 'INV-2024-002', 300, 180, 1800, 0),
(3, '2024-12-03 09:20:00', 'INV-2024-003', 600, 320, 3200, 0),
(4, '2024-12-04 16:50:00', 'INV-2024-004', 400, 210, 2100, 0),
(5, '2024-12-05 11:35:00', 'INV-2024-005', 500, 280, 2800, 0),
(6, '2024-12-06 13:25:00', 'INV-2024-006', 300, 190, 1900, 0),
(7, '2024-12-07 08:50:00', 'INV-2025-001', 400, 240, 2400, 0),
(8, '2024-12-08 15:15:00', 'INV-2025-002', 300, 170, 1700, 0),
(9, '2024-12-09 12:35:00', 'INV-2025-003', 500, 260, 2600, 0),
(10, '2024-12-10 14:20:00', 'INV-2025-004', 400, 220, 2200, 0),
(11, '2024-12-11 10:50:00', 'INV-2025-005', 600, 300, 3000, 0),
(12, '2024-12-12 16:25:00', 'INV-2025-006', 300, 185, 1850, 0),
(13, '2024-12-13 09:35:00', 'INV-2025-007', 500, 270, 2700, 0),
(14, '2024-12-14 13:50:00', 'INV-2025-008', 300, 195, 1950, 0),
(15, '2024-12-15 11:25:00', 'INV-2025-009', 400, 230, 2300, 0);

-- 4. TBL_INVOICE - Invoice records
INSERT INTO `tbl_invoice` (`billing_id`, `employee_id`, `payment_method_id`, `invoice_date`, `invoice_time`, `invoice_total_amount`, `invoice_status_id`) VALUES
(1, 1, 1, '2024-12-01', '10:40:00', 2500, 1),
(2, 1, 1, '2024-12-02', '14:30:00', 1800, 1),
(3, 1, 1, '2024-12-03', '09:25:00', 3200, 1),
(4, 1, 1, '2024-12-04', '16:55:00', 2100, 1),
(5, 1, 1, '2024-12-05', '11:40:00', 2800, 1),
(6, 1, 1, '2024-12-06', '13:30:00', 1900, 1),
(7, 1, 1, '2024-12-07', '08:55:00', 2400, 1),
(8, 1, 1, '2024-12-08', '15:20:00', 1700, 1),
(9, 1, 1, '2024-12-09', '12:40:00', 2600, 1),
(10, 1, 1, '2024-12-10', '14:25:00', 2200, 1),
(11, 1, 1, '2024-12-11', '10:55:00', 3000, 1),
(12, 1, 1, '2024-12-12', '16:30:00', 1850, 1),
(13, 1, 1, '2024-12-13', '09:40:00', 2700, 1),
(14, 1, 1, '2024-12-14', '13:55:00', 1950, 1),
(15, 1, 1, '2024-12-15', '11:30:00', 2300, 1);

-- 5. Additional bookings for different months to test calendar filtering
INSERT INTO `tbl_booking` (`customers_id`, `customers_walk_in_id`, `guests_amnt`, `booking_totalAmount`, `booking_downpayment`, `reference_no`, `booking_checkin_dateandtime`, `booking_checkout_dateandtime`, `booking_created_at`, `booking_isArchive`) VALUES
(NULL, 4, 1, 2000, 400, 'REF-2025-010', '2025-02-01 14:00:00', '2025-02-03 11:00:00', '2024-12-16 10:00:00', 0),
(NULL, 5, 2, 1600, 300, 'REF-2025-011', '2025-02-05 15:00:00', '2025-02-07 12:00:00', '2024-12-17 11:00:00', 0),
(NULL, 6, 3, 2400, 400, 'REF-2025-012', '2025-02-10 16:00:00', '2025-02-12 10:00:00', '2024-12-18 12:00:00', 0),
(NULL, 7, 3, 1800, 300, 'REF-2025-013', '2025-02-15 13:00:00', '2025-02-17 11:00:00', '2024-12-19 13:00:00', 0),
(NULL, 8, 4, 2200, 400, 'REF-2025-014', '2025-02-20 14:30:00', '2025-02-22 12:00:00', '2024-12-20 14:00:00', 0);

INSERT INTO `tbl_booking_room` (`booking_id`, `roomtype_id`, `roomnumber_id`, `bookingRoom_adult`, `bookingRoom_children`) VALUES
(16, 1, 16, 1, 0),
(17, 2, 17, 2, 0),
(18, 3, 18, 2, 1),
(19, 4, 19, 3, 0),
(20, 5, 20, 4, 1);

INSERT INTO `tbl_billing` (`booking_id`, `billing_dateandtime`, `billing_invoice_number`, `billing_downpayment`, `billing_vat`, `billing_total_amount`, `billing_balance`) VALUES
(16, '2024-12-16 10:05:00', 'INV-2025-010', 400, 200, 2000, 0),
(17, '2024-12-17 11:05:00', 'INV-2025-011', 300, 160, 1600, 0),
(18, '2024-12-18 12:05:00', 'INV-2025-012', 400, 240, 2400, 0),
(19, '2024-12-19 13:05:00', 'INV-2025-013', 300, 180, 1800, 0),
(20, '2024-12-20 14:05:00', 'INV-2025-014', 400, 220, 2200, 0);

INSERT INTO `tbl_invoice` (`billing_id`, `employee_id`, `payment_method_id`, `invoice_date`, `invoice_time`, `invoice_total_amount`, `invoice_status_id`) VALUES
(16, 1, 1, '2024-12-16', '10:10:00', 2000, 1),
(17, 1, 1, '2024-12-17', '11:10:00', 1600, 1),
(18, 1, 1, '2024-12-18', '12:10:00', 2400, 1),
(19, 1, 1, '2024-12-19', '13:10:00', 1800, 1),
(20, 1, 1, '2024-12-20', '14:10:00', 2200, 1);

-- 6. Some bookings without invoices (for testing active bookings)
INSERT INTO `tbl_booking` (`customers_id`, `customers_walk_in_id`, `guests_amnt`, `booking_totalAmount`, `booking_downpayment`, `reference_no`, `booking_checkin_dateandtime`, `booking_checkout_dateandtime`, `booking_created_at`, `booking_isArchive`) VALUES
(NULL, 9, 1, 1900, 300, 'REF-2025-015', '2025-03-01 14:00:00', '2025-03-03 11:00:00', '2024-12-21 10:00:00', 0),
(NULL, 10, 2, 2100, 400, 'REF-2025-016', '2025-03-05 15:00:00', '2025-03-07 12:00:00', '2024-12-22 11:00:00', 0),
(NULL, 11, 3, 1700, 300, 'REF-2025-017', '2025-03-10 16:00:00', '2025-03-12 10:00:00', '2024-12-23 12:00:00', 0);

INSERT INTO `tbl_booking_room` (`booking_id`, `roomtype_id`, `roomnumber_id`, `bookingRoom_adult`, `bookingRoom_children`) VALUES
(21, 1, 21, 1, 0),
(22, 2, 22, 2, 0),
(23, 3, 23, 2, 1);

-- Note: No billing or invoice records for bookings 21-23 to test active bookings functionality

-- 7. Additional 2025 invoice data for testing
INSERT INTO `tbl_billing` (`booking_id`, `billing_dateandtime`, `billing_invoice_number`, `billing_downpayment`, `billing_vat`, `billing_total_amount`, `billing_balance`) VALUES
(21, '2025-12-01 10:05:00', 'INV-2025-015', 300, 190, 1900, 0),
(22, '2025-12-02 11:05:00', 'INV-2025-016', 400, 210, 2100, 0),
(23, '2025-12-03 12:05:00', 'INV-2025-017', 300, 170, 1700, 0);

INSERT INTO `tbl_invoice` (`billing_id`, `employee_id`, `payment_method_id`, `invoice_date`, `invoice_time`, `invoice_total_amount`, `invoice_status_id`) VALUES
(21, 1, 1, '2025-12-01', '10:10:00', 1900, 1),
(22, 1, 1, '2025-12-02', '11:10:00', 2100, 1),
(23, 1, 1, '2025-12-03', '12:10:00', 1700, 1);
