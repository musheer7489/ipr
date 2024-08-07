<?php
session_start();

include 'db_connection.php';
$response = array('status' => 'error', 'message' => 'Invalid request');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
    $mobileNumber = $_POST['mobileNumber'];
    $email = $_POST['email'];

    // Check for existing mobile number or email
    $checkSql = "SELECT id FROM applicants WHERE mobile_number=? OR email=?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $mobileNumber, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Mobile number or email already exists.');
    } else {
        // Insert new record
        $insertSql = "INSERT INTO applicants (full_name, dob, mobile_number, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssss", $fullName, $dob, $mobileNumber, $email);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $response = array('status' => 'success', 'message' => 'Registration successful. Now Login to complete Application Form');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to register. Please try again.');
        }
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
