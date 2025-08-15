<?php

include 'header.php';

// Goals for Admin Functions 
// Importance (From Top to Bottom)
// 1. Can book a customer into a room
// 2. Can cancel a booking
// 3. Can update a booking
// 4. Can view all booking

class FrontDesk_Functions
{
    // Views the Rooms that are currently Booked and it's details
    function view_booking()
    {
        include 'connection.php';

        $sql = "SELECT 
    a.booking_id, 
    CONCAT(d.customers_walk_in_fname, ' ', d.customers_walk_in_lname) AS fullname,
    e.customers_online_username, 
    a.booking_downpayment, 
    s.booking_status_name, 
    a.booking_checkin_dateandtime, 
    a.booking_checkout_dateandtime,
    a.reference_no,
    a.booking_created_at, 
    h.updated_at,

    CASE 
        WHEN d.customers_walk_in_fname IS NOT NULL AND d.customers_walk_in_lname IS NOT NULL THEN 'Walk-In'
        WHEN e.customers_online_username IS NOT NULL THEN 'Online'
        ELSE NULL
    END AS customer_type

FROM tbl_booking a
LEFT JOIN tbl_customers_walk_in d ON a.customers_walk_in_id = d.customers_walk_in_id
LEFT JOIN tbl_customers_online e ON a.customers_id = e.customers_online_id

LEFT JOIN (
  SELECT bh1.*
  FROM tbl_booking_history bh1
  INNER JOIN (
    SELECT booking_id, MAX(updated_at) AS latest_update
    FROM tbl_booking_history
    GROUP BY booking_id
  ) bh2 ON bh1.booking_id = bh2.booking_id AND bh1.updated_at = bh2.latest_update
) h ON a.booking_id = h.booking_id

LEFT JOIN tbl_booking_status s ON h.status_id = s.booking_status_id
      ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $conn);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // For Dashboard Page
    function viewAllRooms()
    {
        include "connection.php";

        $sql = "SELECT 
                a.roomnumber_id,
                a.roomtype_id,
                a.roomfloor,
                a.room_capacity,
                a.room_beds,
                a.room_sizes,
                b.status_name
                FROM tbl_rooms a
                INNER JOIN tbl_status_types b ON a.room_status_id = b.status_id;";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rowCount = $stmt->rowCount();

            return $rowCount > 0 ? json_encode($result) : 0;
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    function recBooking_Status($data)
    {
        include 'connection.php';

        try {
            // Reminder to accept Employee ID
            // $emp_id = intval($data["emp_id"]);
            $book_id = intval($data["booking_id"]);
            $status_id = intval($data["booking_status_id"]);

            $stmt  = $conn->prepare(
                "INSERT INTO tbl_booking_history (booking_id, employee_id, status_id,updated_at)
                VALUES (:booking_id, 1, :status_id, NOW())"
            );

            $stmt->bindParam(":booking_id", $book_id);
            // $stmt->bindParam(":employee_id", $emp_id);
            $stmt->bindParam(":status_id", $status_id);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            unset($stmt, $conn);

            return $rowCount > 0 ? json_encode(["success" => true]) : json_encode(["success" => false]);
        } catch (PDOException $e) {
            return json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }


    function viewRooms()
    {
        include "connection.php";

        $stmt = $conn->prepare(
            "SELECT b.roomtype_id AS room_type,
		b.max_capacity,
        b.roomtype_description,
        b.roomtype_name, 
        GROUP_CONCAT(DISTINCT a.imagesroommaster_filename) AS images,
        b.roomtype_price, 
        GROUP_CONCAT(DISTINCT c.roomnumber_id) AS room_ids,
        GROUP_CONCAT(DISTINCT CONCAT('Floor:', c.roomfloor, ' Beds:', c.room_beds)) AS room_details,
        GROUP_CONCAT(DISTINCT e.room_amenities_master_name) AS amenities,
        f.status_name,
        f.status_id
        FROM tbl_roomtype b
        LEFT JOIN tbl_imagesroommaster a ON a.roomtype_id = b.roomtype_id
        LEFT JOIN tbl_rooms c ON b.roomtype_id = c.roomtype_id
        LEFT JOIN tbl_amenity_roomtype d ON b.roomtype_id = d.roomtype_id
        LEFT JOIN tbl_room_amenities_master e ON d.room_amenities_master = e.room_amenities_master_id
        LEFT JOIN tbl_status_types f ON f.status_id = c.room_status_id
        GROUP BY b.roomtype_id, b.roomtype_name, b.roomtype_price
        "
        );

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    function updateMyProfile($data) {
    include "connection.php"; // assumes $conn is your PDO object

    try {
        $sql = "UPDATE tbl_employee 
            SET 
                employee_fname = :name,
                employee_lname = :lastName
                employee_username= :userName
                phone = :phone
                email = :email,
                employee_updated_at = NOW()
            WHERE user_id = :userId";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $data['firstName']);
        $stmt->bindParam(':lastName', $data['lastName']);
        $stmt->bindParam(':email', $data['emailAddress']);
        $stmt->bindParam(':phone', $data['phoneNumber']);
        $stmt->bindParam(':userName', $data['userName']);
        $stmt->bindParam(':userId', $data['userID']);

        $stmt->execute();

        return $stmt->rowCount() > 0 ? "success" : "no changes";

    } catch (PDOException $e) {
        return "error: " . $e->getMessage();
    }
}


    // Add Guests
    function customerWalkIn($data)
    {
        include "connection.php";

        try {
            $conn->beginTransaction();

            // Insert walk-in customer
            $stmt = $conn->prepare("
                INSERT INTO tbl_customers_walk_in 
                    (customers_walk_in_fname, customers_walk_in_lname, customers_walk_in_email, customers_walk_in_phone_number) 
                VALUES 
                    (:customers_walk_in_fname, :customers_walk_in_lname, :customers_walk_in_email, :customers_walk_in_phone_number)
            ");
            $stmt->bindParam(":customers_walk_in_fname", $data["customers_walk_in_fname"]);
            $stmt->bindParam(":customers_walk_in_lname", $data["customers_walk_in_lname"]);
            $stmt->bindParam(":customers_walk_in_email", $data["customers_walk_in_email"]);
            $stmt->bindParam(":customers_walk_in_phone_number", $data["customers_walk_in_phone_number"]);
            $stmt->execute();
            $walkInCustomerId = $conn->lastInsertId();

            // Insert booking
            $stmt = $conn->prepare("
                INSERT INTO tbl_booking 
                    (customers_id, customers_walk_in_id, booking_status_id, booking_downpayment, booking_checkin_dateandtime, booking_checkout_dateandtime, booking_created_at) 
                VALUES 
                    (NULL, :customers_walk_in_id, 2, :booking_downpayment, :booking_checkin_dateandtime, :booking_checkout_dateandtime, NOW())
            ");
            $stmt->bindParam(":customers_walk_in_id", $walkInCustomerId);
            $stmt->bindParam(":booking_downpayment", $data["booking_downpayment"]);
            $stmt->bindParam(":booking_checkin_dateandtime", $data["booking_checkin_dateandtime"]);
            $stmt->bindParam(":booking_checkout_dateandtime", $data["booking_checkout_dateandtime"]);
            $stmt->execute();
            $bookingId = $conn->lastInsertId();

            // Insert into tbl_booking_room based on room quantity
            $roomtype_id = $data["roomtype_id"];
            $room_count = intval($data["room_count"]);

            for ($i = 0; $i < $room_count; $i++) {
                $stmt = $conn->prepare("
                    INSERT INTO tbl_booking_room 
                        (booking_id, roomtype_id, roomnumber_id) 
                    VALUES 
                        (:booking_id, :roomtype_id, NULL)
                ");
                $stmt->bindParam(":booking_id", $bookingId);
                $stmt->bindParam(":roomtype_id", $roomtype_id);
                $stmt->execute();
            }

            $conn->commit();
            return 1;
        } catch (PDOException $e) {
            $conn->rollBack();
            return 0;
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
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $conn);
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
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $conn);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Updated Log"]);
        }
    }
}


$demiren_FrontDesk = new FrontDesk_Functions();
$method = isset($_POST["method"]) ? $_POST["method"] : 0;
$json = isset($_POST["json"]) ? json_decode($_POST["json"], true) : 0;

switch ($method) {
    case 'viewReservations':
        echo $demiren_FrontDesk->view_booking();
        break;
    case 'recordBookingStatus':
        echo $demiren_FrontDesk->recBooking_Status($json);
        break;

    // Guests
    case 'seeRooms':
        echo $demiren_FrontDesk->viewRooms();
        break;

    case 'customer-walkIn':
        echo $demiren_FrontDesk->customerWalkIn($json);
        break;
    // Room Availablity
    case 'available-rooms':
        break;
    case 'edit-room':
        break;

    // Dashboard
    case 'roomAmnt':
        echo $demiren_FrontDesk->viewAllRooms();
        break;

        // Profile
        case 'updateProfile':
            echo $demiren_FrontDesk->updateMyProfile($json);
            break;
    default:
        echo "Method Unavailable...";
        break;
}
