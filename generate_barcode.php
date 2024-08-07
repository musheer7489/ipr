<?php
session_start();
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

// Initialize the generator
$generator = new BarcodeGeneratorPNG();

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Include database connection
include 'db_connection.php';

// Fetch user data from the database
$sql = "SELECT * FROM applicants WHERE id = $user_id";
$result = $conn->query($sql);

// Check if user data exists
if ($result->num_rows > 0) {
    // Fetch user data
    $row = $result->fetch_assoc();
    $applicationNumber = $row['id'];
    $fullName = $row['full_name'];
}
else {
    $error_message = "User data not found.";
}
//define values
$values = [$applicationNumber, $fullName];
$barcodeString = implode(',',$values);
// Generate the barcode
$barcode = $generator->getBarcode($barcodeString, $generator::TYPE_CODE_128);

// Set the content type header - this is important for displaying the image
header('Content-Type: image/png');

// Output the barcode image
echo $barcode;
?>
