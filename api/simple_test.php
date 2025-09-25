<?php
// Simple test script for transactions.php
echo "<h1>Testing Transactions.php Functions</h1>";
echo "<hr>";

// Test function to simulate POST requests
function testFunction($operation, $json = null) {
    echo "<h3>Testing: $operation</h3>";
    
    // Simulate the POST data
    $_POST['operation'] = $operation;
    if ($json !== null) {
        $_POST['json'] = json_encode($json);
    }
    
    // Capture output
    ob_start();
    
    try {
        // Include the transactions.php file
        include "transactions.php";
        $output = ob_get_contents();
    } catch (Exception $e) {
        $output = "Error: " . $e->getMessage();
    }
    
    ob_end_clean();
    
    echo "<strong>Response:</strong> <pre>" . htmlspecialchars($output) . "</pre>";
    echo "<hr>";
    
    // Clean up
    unset($_POST['operation']);
    unset($_POST['json']);
}

// Test 1: Basic functions without parameters
echo "<h2>1. Testing Basic Functions</h2>";
testFunction('bookingList');
testFunction('chargesMasterList');
testFunction('bookingChargesList');
testFunction('getChargesCategory');
testFunction('getRooms');
testFunction('getBookingsWithBillingStatus');

// Test 2: Functions with parameters
echo "<h2>2. Testing Functions with Parameters</h2>";

// Test with sample booking ID
$testData = ['booking_id' => 1];
testFunction('validateBillingCompleteness', $testData);
testFunction('getBookingCharges', $testData);
testFunction('getDetailedBookingCharges', $testData);
testFunction('getBookingInvoice', $testData);

// Test billing calculation
$billingData = [
    'booking_id' => 1,
    'discount_id' => null,
    'vat_rate' => 0.12,
    'downpayment' => 0
];
testFunction('calculateComprehensiveBilling', $billingData);

// Test create billing record
$createBillingData = [
    'booking_id' => 1,
    'employee_id' => 1
];
testFunction('createBillingRecord', $createBillingData);

// Test 3: Error handling
echo "<h2>3. Testing Error Handling</h2>";
testFunction('invalidOperation');
testFunction('getVacantRoomsByBooking', []); // Missing reference_no

echo "<h2>Test Complete</h2>";
echo "<p>All functions have been tested. Check the responses above for any errors.</p>";
?>
