<?php
// Test script for transactions.php functions
include "connection.php";

echo "<h1>Testing Transactions.php Functions</h1>";
echo "<hr>";

// Test data
$test_booking_id = 1; // Assuming booking ID 1 exists
$test_reference_no = "REF20250101001"; // Test reference number

// Function to make POST requests to transactions.php
function testTransactionFunction($operation, $json = null) {
    $url = "http://localhost/demirenAPI/api/transactions.php";
    
    $postData = [
        'operation' => $operation
    ];
    
    if ($json !== null) {
        $postData['json'] = json_encode($json);
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'operation' => $operation
    ];
}

// Test 1: Basic Booking Operations
echo "<h2>1. Testing Basic Booking Operations</h2>";

// Test bookingList
echo "<h3>1.1 Testing bookingList()</h3>";
$result = testTransactionFunction('bookingList');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test getVacantRoomsByBooking
echo "<h3>1.2 Testing getVacantRoomsByBooking()</h3>";
$testData = ['reference_no' => $test_reference_no];
$result = testTransactionFunction('getVacantRoomsByBooking', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test getRooms
echo "<h3>1.3 Testing getRooms()</h3>";
$result = testTransactionFunction('getRooms');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";

// Test 2: Charges Management
echo "<h2>2. Testing Charges Management Functions</h2>";

// Test chargesMasterList
echo "<h3>2.1 Testing chargesMasterList()</h3>";
$result = testTransactionFunction('chargesMasterList');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test getChargesCategory
echo "<h3>2.2 Testing getChargesCategory()</h3>";
$result = testTransactionFunction('getChargesCategory');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test bookingChargesList
echo "<h3>2.3 Testing bookingChargesList()</h3>";
$result = testTransactionFunction('bookingChargesList');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";

// Test 3: Billing and Invoice Functions
echo "<h2>3. Testing Billing and Invoice Functions</h2>";

// Test getBookingsWithBillingStatus
echo "<h3>3.1 Testing getBookingsWithBillingStatus()</h3>";
$result = testTransactionFunction('getBookingsWithBillingStatus');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test validateBillingCompleteness
echo "<h3>3.2 Testing validateBillingCompleteness()</h3>";
$testData = ['booking_id' => $test_booking_id];
$result = testTransactionFunction('validateBillingCompleteness', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test calculateComprehensiveBilling
echo "<h3>3.3 Testing calculateComprehensiveBilling()</h3>";
$testData = [
    'booking_id' => $test_booking_id,
    'discount_id' => null,
    'vat_rate' => 0.12,
    'downpayment' => 0
];
$result = testTransactionFunction('calculateComprehensiveBilling', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test createBillingRecord
echo "<h3>3.4 Testing createBillingRecord()</h3>";
$testData = [
    'booking_id' => $test_booking_id,
    'employee_id' => 1
];
$result = testTransactionFunction('createBillingRecord', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";

// Test 4: Booking Charges Functions
echo "<h2>4. Testing Booking Charges Functions</h2>";

// Test getBookingCharges
echo "<h3>4.1 Testing getBookingCharges()</h3>";
$testData = ['booking_id' => $test_booking_id];
$result = testTransactionFunction('getBookingCharges', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test getDetailedBookingCharges
echo "<h3>4.2 Testing getDetailedBookingCharges()</h3>";
$testData = ['booking_id' => $test_booking_id];
$result = testTransactionFunction('getDetailedBookingCharges', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test addBookingCharge
echo "<h3>4.3 Testing addBookingCharge()</h3>";
$testData = [
    'booking_id' => $test_booking_id,
    'charge_name' => 'Test Charge',
    'charge_price' => 100,
    'quantity' => 1,
    'category_id' => 4,
    'employee_id' => 1
];
$result = testTransactionFunction('addBookingCharge', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";

// Test 5: Invoice Functions
echo "<h2>5. Testing Invoice Functions</h2>";

// Test getBookingInvoice
echo "<h3>5.1 Testing getBookingInvoice()</h3>";
$testData = ['booking_id' => $test_booking_id];
$result = testTransactionFunction('getBookingInvoice', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test createInvoice (comprehensive test)
echo "<h3>5.2 Testing createInvoice()</h3>";
$testData = [
    'billing_ids' => [1], // Assuming billing ID 1 exists
    'employee_id' => 1,
    'payment_method_id' => 1,
    'invoice_status_id' => 1,
    'discount_id' => null,
    'vat_rate' => 0.12,
    'downpayment' => 0
];
$result = testTransactionFunction('createInvoice', $testData);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";

// Test 6: Error Handling
echo "<h2>6. Testing Error Handling</h2>";

// Test with invalid operation
echo "<h3>6.1 Testing Invalid Operation</h3>";
$result = testTransactionFunction('invalidOperation');
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

// Test with missing data
echo "<h3>6.2 Testing Missing Data</h3>";
$result = testTransactionFunction('getVacantRoomsByBooking', []);
echo "<strong>HTTP Code:</strong> " . $result['http_code'] . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

echo "<hr>";
echo "<h2>Test Summary</h2>";
echo "<p>All tests completed. Check the responses above for any errors or issues.</p>";
echo "<p><strong>Note:</strong> Some tests may fail if the database doesn't contain the expected test data.</p>";
?>
