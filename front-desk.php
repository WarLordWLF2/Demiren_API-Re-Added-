<?php

include 'header.php';

// Goals for Admin Functions 
// Importance (From Top to Bottom)
// 1. Can book a customer into a room
// 2. Can cancel a booking
// 3. Can update a booking
// 4. Can view all booking

class Booking_Functions
{
    // Views the Rooms that are currently Booked and it's details
    function view_booking()
    {
        include 'connection.php';

        $sql = "SELECT a.reservation_status_id, CONCAT(c.guests_user_fname, ' ', c.guests_user_lname) AS guest_name, b.reservation_online_num_of_guest, 
                b.reservation_online_adult, b.reservation_online_children, b.reservation_online_roomtype_id FROM tbl_reservation_status a 
                INNER JOIN tbl_reservation_online b ON a.reservation_online_id = b.reservation_online_id
                INNER JOIN tbl_guests c ON b.reservation_online_guest_id = c.guests_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo, $stmt);

        if ($results) {
            return json_encode(["response" => true, "data" => $results, "message" => "Fetched All Available Bookings"]);
        } else {
            return json_encode(["response" => false, "message" => "Could not find anything..."]);
        }
    }

    // Customer Gets Room from Front Desk
    function add_booking($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "INSERT INTO timeanddate( guest_name, time_arrival, date_arrival, num_of_guest, 
                adult, children, roomtype_id, created_at, updated_at) 
                VALUES (:name, :time_arrival, :date_arrival, :num_of_guest, :adult, :children, :roomtype_id, 
                :created_at, :updated_at)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $decodeData["name"]);
        $stmt->bindParam(":time_arrival", $decodeData["time_arrival"]);
        $stmt->bindParam(":date_arrival", $decodeData["date_arrival"]);
        $stmt->bindParam(":num_of_guest", $decodeData["num_of_guest"]);
        $stmt->bindParam(":adult", $decodeData["adult"]);
        $stmt->bindParam(":children", $decodeData["children"]);
        $stmt->bindParam(":roomtype_id", $decodeData["roomtype_id"]);

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            json_encode(["response" => true, "message" => "Successfully Added New Schedule"]);
        }
    }

    // Add Guests
    function newGuests($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "INSERT INTO tbl_guests(guests_user_fname, guests_user_lname, guests_user_country, guests_user_email,
                guests_user_phone, guests_user_age)
                VALUES(:fname, :lname, :country, :email, :phone, :age)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":fname", $decodeData[""]);
        $stmt->bindParam(":lname", $decodeData[""]);
        $stmt->bindParam(":country", $decodeData[""]);
        $stmt->bindParam(":email", $decodeData[""]);
        $stmt->bindParam(":phone", $decodeData[""]);
        $stmt->bindParam(":age", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $pdo);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Added Guest"]);
        }
    }

    // Front Desk Checks In and Checks Out
    function visitor_logs($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "INSERT INTO tbl_guests(visitorlogs_guest_id, visitorlogs_visitorname , visitorlogs_purpose , visitorlogs_checkin_time,
                visitorlogs_checkout_time)
                VALUES(:guest, :visitor_name, :purpose, :check_in, :check_out)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $pdo);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Added New Log"]);
        }
    }

    // Can change Check In Check Out
    function change_visitor_logs($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "UPDATE tbl_guests 
                SET visitorlogs_guest_id = :guest, visitorlogs_visitorname = :visitor_name, visitorlogs_purpose = :purpose, 
                visitorlogs_checkin_time = :check_in, visitorlogs_checkout_time = :check_out)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $pdo);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Updated Log"]);
        }
    }

    // ------------------------------------------------------- Booking Management Functions ------------------------------------------------------- //

    // View all bookings with comprehensive details
    function viewBookingList()
    {
        include 'connection.php';

        try {
            $sql = "SELECT 
                        b.booking_id,
                        b.reference_no,
                        b.booking_checkin_dateandtime,
                        b.booking_checkout_dateandtime,
                        b.booking_created_at,
                        -- Customer core
                        COALESCE(CONCAT(c.customers_fname, ' ', c.customers_lname),
                                 CONCAT(w.customers_walk_in_fname, ' ', w.customers_walk_in_lname)) AS customer_name,
                        COALESCE(c.customers_email, w.customers_walk_in_email) AS customer_email,
                        COALESCE(c.customers_phone, w.customers_walk_in_phone) AS customer_phone,
                        n.nationality_name AS nationality,
                        -- Rooms
                        GROUP_CONCAT(br.roomnumber_id ORDER BY br.booking_room_id ASC) AS room_numbers,
                        -- Latest status
                        CASE 
                            WHEN COALESCE(bs.booking_status_name, 'Pending') = 'Pending' AND b.booking_checkout_dateandtime < NOW() THEN 'Checked-Out'
                            ELSE COALESCE(bs.booking_status_name, 'Pending')
                        END AS booking_status,
                        -- Amounts
                        COALESCE(bill.billing_total_amount, b.booking_totalAmount) AS total_amount,
                        COALESCE(bill.billing_downpayment, b.booking_downpayment) AS downpayment
                    FROM tbl_booking b
                    LEFT JOIN tbl_customers c 
                        ON b.customers_id = c.customers_id
                    LEFT JOIN tbl_customers_walk_in w 
                        ON b.customers_walk_in_id = w.customers_walk_in_id
                    LEFT JOIN tbl_nationality n 
                        ON c.nationality_id = n.nationality_id
                    LEFT JOIN tbl_booking_room br 
                        ON b.booking_id = br.booking_id
                    LEFT JOIN (
                        SELECT bh1.booking_id, bs.booking_status_name
                        FROM tbl_booking_history bh1
                        INNER JOIN (
                            SELECT booking_id, MAX(booking_history_id) AS latest_history_id
                            FROM tbl_booking_history
                            GROUP BY booking_id
                        ) last ON last.booking_id = bh1.booking_id AND last.latest_history_id = bh1.booking_history_id
                        INNER JOIN tbl_booking_status bs ON bh1.status_id = bs.booking_status_id
                    ) bs ON bs.booking_id = b.booking_id
                    LEFT JOIN (
                        SELECT bi.booking_id,
                               MAX(bi.billing_id) AS latest_billing_id
                        FROM tbl_billing bi
                        GROUP BY bi.booking_id
                    ) lb ON lb.booking_id = b.booking_id
                    LEFT JOIN tbl_billing bill 
                        ON bill.billing_id = lb.latest_billing_id
                    GROUP BY b.booking_id
                    ORDER BY b.booking_created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            unset($pdo, $stmt);

            return !empty($result) ? json_encode($result) : json_encode([]);
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    // Change booking status
    function changeBookingStatus($data)
    {
        include 'connection.php';

        try {
            $pdo->beginTransaction();

            $book_id = intval($data["booking_id"]);
            $status_id = intval($data["booking_status_id"]);
            $employee_id = intval($data["employee_id"] ?? 1); // Default to admin if not provided
            $room_ids = $data["room_ids"] ?? []; // Array of room IDs to update

            // 1. Insert booking history record
            $stmt = $pdo->prepare(
                "INSERT INTO tbl_booking_history (booking_id, employee_id, status_id, updated_at)
                VALUES (:booking_id, :employee_id, :status_id, NOW())"
            );
            $stmt->bindParam(":booking_id", $book_id);
            $stmt->bindParam(":employee_id", $employee_id);
            $stmt->bindParam(":status_id", $status_id);
            $stmt->execute();

            // 2. Handle room status changes based on booking status
            if (!empty($room_ids)) {
                $room_status_id = null;

                // Determine room status based on booking status
                switch ($status_id) {
                    case 5: // Checked-In
                        $room_status_id = 1; // Occupied
                        break;
                    case 4: // Checked-Out
                        $room_status_id = 5; // Dirty (needs cleaning)
                        break;
                    case 3: // Cancelled
                        $room_status_id = 3; // Vacant
                        break;
                    case 2: // Approved
                        $room_status_id = 1; // Occupied
                        break;
                }

                // Update room statuses if we have a valid room status
                if ($room_status_id !== null) {
                    $room_stmt = $pdo->prepare(
                        "UPDATE tbl_rooms SET room_status_id = :room_status_id 
                         WHERE roomnumber_id = :room_id"
                    );

                    foreach ($room_ids as $room_id) {
                        $room_stmt->bindParam(":room_status_id", $room_status_id);
                        $room_stmt->bindParam(":room_id", $room_id);
                        $room_stmt->execute();
                    }
                }
            }

            // 3. If booking is approved and no specific rooms provided, get rooms from booking_room table
            if ($status_id == 2 && empty($room_ids)) {
                // Get all rooms associated with this booking
                $room_query = $pdo->prepare(
                    "SELECT roomnumber_id FROM tbl_booking_room 
                     WHERE booking_id = :booking_id AND roomnumber_id IS NOT NULL"
                );
                $room_query->bindParam(":booking_id", $book_id);
                $room_query->execute();
                $booking_rooms = $room_query->fetchAll(PDO::FETCH_COLUMN);

                // Update all booking rooms to Occupied
                if (!empty($booking_rooms)) {
                    $room_stmt = $pdo->prepare(
                        "UPDATE tbl_rooms SET room_status_id = 1 
                         WHERE roomnumber_id = :room_id"
                    );

                    foreach ($booking_rooms as $room_id) {
                        $room_stmt->bindParam(":room_id", $room_id);
                        $room_stmt->execute();
                    }
                }
            }

            $pdo->commit();
            return json_encode([
                "success" => true,
                "message" => "Booking status updated successfully",
                "booking_id" => $book_id,
                "status_id" => $status_id,
                "rooms_updated" => count($room_ids)
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            return json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }

    // Get all booking statuses
    function getAllBookingStatus()
    {
        include 'connection.php';

        try {
            $sql = "SELECT * FROM tbl_booking_status ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($statuses);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ------------------------------------------------------- Transaction History Functions ------------------------------------------------------- //

    // Get transaction history with filters
    function getTransactionHistory($data)
    {
        include "connection.php";

        try {
            $limit = isset($data['limit']) ? (int)$data['limit'] : 50;
            $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
            $transaction_type = isset($data['transaction_type']) ? $data['transaction_type'] : 'all';
            $status_filter = isset($data['status_filter']) ? $data['status_filter'] : 'all';
            $date_from = isset($data['date_from']) ? $data['date_from'] : null;
            $date_to = isset($data['date_to']) ? $data['date_to'] : null;

            // Start with a simple booking query first
            $sql = "SELECT 
                        'booking' as transaction_type,
                        b.booking_id as transaction_id,
                        b.reference_no,
                        CONCAT(COALESCE(c.customers_fname, w.customers_walk_in_fname), ' ', COALESCE(c.customers_lname, w.customers_walk_in_lname)) AS customer_name,
                        COALESCE(c.customers_email, w.customers_walk_in_email) AS customer_email,
                        COALESCE(c.customers_phone, w.customers_walk_in_phone) AS customer_phone,
                        b.booking_totalAmount as amount,
                        b.booking_downpayment,
                        'Active' as status,
                        b.booking_checkin_dateandtime,
                        b.booking_checkout_dateandtime,
                        b.booking_created_at as transaction_date,
                        'Booking created/updated' as description,
                        'frontdesk' as performed_by_type,
                        'Front Desk' as performed_by_name,
                        '' as room_number,
                        '' as amenity_name,
                        'success' as status_color
                    FROM tbl_booking b
                    LEFT JOIN tbl_customers c ON b.customers_id = c.customers_id
                    LEFT JOIN tbl_customers_walk_in w ON b.customers_walk_in_id = w.customers_walk_in_id
                    WHERE b.booking_isArchive = 0";

            // Add filters
            $where_conditions = [];
            $params = [];

            if ($transaction_type !== 'all' && $transaction_type !== 'booking') {
                // If not booking type, return empty for now
                return [
                    'success' => true,
                    'transactions' => [],
                    'total_count' => 0,
                    'current_page' => 1,
                    'total_pages' => 1
                ];
            }

            if ($status_filter !== 'all') {
                // Since we don't have booking status, we'll skip this filter for now
            }

            if ($date_from) {
                $where_conditions[] = "DATE(b.booking_created_at) >= :date_from";
                $params[':date_from'] = $date_from;
            }

            if ($date_to) {
                $where_conditions[] = "DATE(b.booking_created_at) <= :date_to";
                $params[':date_to'] = $date_to;
            }

            // Add WHERE conditions
            if (!empty($where_conditions)) {
                $sql .= " AND " . implode(" AND ", $where_conditions);
            }

            $sql .= " ORDER BY b.booking_created_at DESC LIMIT :limit OFFSET :offset";

            $stmt = $pdo->prepare($sql);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get total count
            $count_sql = "SELECT COUNT(*) as total FROM tbl_booking b
                         LEFT JOIN tbl_customers c ON b.customers_id = c.customers_id
                         LEFT JOIN tbl_customers_walk_in w ON b.customers_walk_in_id = w.customers_walk_in_id
                         WHERE b.booking_isArchive = 0";
            
            if (!empty($where_conditions)) {
                $count_sql .= " AND " . implode(" AND ", $where_conditions);
            }
            
            $count_stmt = $pdo->prepare($count_sql);
            foreach ($params as $key => $value) {
                $count_stmt->bindValue($key, $value);
            }
            $count_stmt->execute();
            $total_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'success' => true,
                'transactions' => $transactions,
                'total_count' => $total_count,
                'current_page' => floor($offset / $limit) + 1,
                'total_pages' => ceil($total_count / $limit)
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'transactions' => [],
                'total_count' => 0,
                'current_page' => 1,
                'total_pages' => 1
            ];
        }
    }

    // Get transaction statistics
    function getTransactionStats()
    {
        include "connection.php";

        try {
            // Simple stats for now - just booking data
            $sql = "SELECT 
                        COUNT(*) as total_transactions,
                        SUM(booking_totalAmount) as total_amount_today
                    FROM tbl_booking 
                    WHERE DATE(booking_created_at) = CURDATE() 
                    AND booking_isArchive = 0";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $today_stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Week stats
            $week_sql = "SELECT 
                            COUNT(*) as total_transactions,
                            SUM(booking_totalAmount) as total_amount_week
                        FROM tbl_booking 
                        WHERE YEARWEEK(booking_created_at) = YEARWEEK(CURDATE()) 
                        AND booking_isArchive = 0";

            $stmt = $pdo->prepare($week_sql);
            $stmt->execute();
            $week_stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Month stats
            $month_sql = "SELECT 
                            COUNT(*) as total_transactions,
                            SUM(booking_totalAmount) as total_amount_month
                        FROM tbl_booking 
                        WHERE YEAR(booking_created_at) = YEAR(CURDATE()) 
                        AND MONTH(booking_created_at) = MONTH(CURDATE()) 
                        AND booking_isArchive = 0";

            $stmt = $pdo->prepare($month_sql);
            $stmt->execute();
            $month_stats = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'today' => $today_stats,
                'week' => $week_stats,
                'month' => $month_stats
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
                'today' => ['total_transactions' => 0, 'total_amount_today' => 0],
                'week' => ['total_transactions' => 0, 'total_amount_week' => 0],
                'month' => ['total_transactions' => 0, 'total_amount_month' => 0]
            ];
        }
    }

    // ------------------------------------------------------- Dashboard Functions ------------------------------------------------------- //

    // Get active bookings for dashboard
    function getActiveBookingsForDashboard()
    {
        include "connection.php";

        try {
            // Count only active bookings that don't have invoices (not checked out)
            $sql = "SELECT 
                        COUNT(DISTINCT b.booking_id) as active_bookings_count
                    FROM tbl_booking b
                    LEFT JOIN tbl_billing bl ON b.booking_id = bl.booking_id
                    LEFT JOIN tbl_invoice i ON bl.billing_id = i.billing_id
                    WHERE b.booking_isArchive = 0
                    AND b.booking_checkin_dateandtime <= NOW()  
                    AND b.booking_checkout_dateandtime >= NOW()
                    AND i.invoice_id IS NULL";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Ensure we return a number, not null
            $activeBookingsCount = $result['active_bookings_count'] ?? 0;

            return json_encode([
                'active_bookings_count' => (int)$activeBookingsCount
            ]);
        } catch (PDOException $e) {
            return json_encode([
                'error' => $e->getMessage(),
                'active_bookings_count' => 0
            ]);
        }
    }
}


$booking = new Booking_Functions();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $svr_Request = $_GET["method"];
    switch ($svr_Request) {
        case "view-booking":
            echo $booking->view_booking();
            break;
        case "viewBookingList":
            echo $booking->viewBookingList();
            break;
        case "getAllBookingStatus":
            echo $booking->getAllBookingStatus();
            break;
        case "getTransactionStats":
            echo json_encode($booking->getTransactionStats());
            break;
        case "getActiveBookingsForDashboard":
            echo $booking->getActiveBookingsForDashboard();
            break;

        default:
            echo json_encode(["response" => false, "message" => "Not Available"]);
    }
    
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $svr_Request = $_POST["method"];
    switch ($svr_Request) {
        case "add-booking":
            $book_data = $_POST[""];
            echo $booking->add_booking($book_data);
            break;

        case "addGuest":
            $data = $_POST["guest_data"];
            echo $booking->newGuests($data);
            break;

        case "changeBookingStatus":
            $data = $_POST;
            echo $booking->changeBookingStatus($data);
            break;

        case "getTransactionHistory":
            $data = $_POST;
            echo json_encode($booking->getTransactionHistory($data));
            break;

        default:
            echo json_encode(["response" => false, "message" => "Not Available"]);
    }

} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {

    $svr_Request = $_PUT["method"];
    switch ($svr_Request) {
        case "upd_Log":
            $book_data = $_PUT["log_data"];
            echo $booking->change_visitor_logs($book_data);
            break;

        default:
            echo json_encode(["response" => false, "message" => "Not Available"]);
    }
} else {
    echo json_encode(["response" => false, "message" => "Request Method not Available..."]);
}
