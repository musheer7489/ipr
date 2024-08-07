<?php
session_start();

// Check if the form is submitted (assuming POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have form fields for mobile number and date of birth
    $mobileNumber = $_POST['mobileNumber']; // Replace with your form field name
    $dateOfBirth = $_POST['dob']; // Replace with your form field name

    // Validate the mobile number and date of birth as needed
    include 'db_connection.php';
    // Check if the user exists in the database
    $sql = "SELECT * FROM applicants WHERE mobile_number = '$mobileNumber' AND dob = '$dateOfBirth'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, check if is_submitted is 'yes'
        $row = $result->fetch_assoc();
        if ($row['is_submitted'] == 'yes') {
            // User has submitted the form, set user_id in session and redirect to preview.php
            $_SESSION['user_id'] = $row['id'];
            header("Location: preview.php");
            exit();
        } else {
            // User has not submitted the form, redirect to form.php
            header("Location: form.php");
            $_SESSION['user_id'] = $row['id'];
            exit();
        }
    } else {
        // User does not exist, redirect back to login page with error message
        header("Location: index.html?error=Mobile Number or Date of Birth is Invalid");
        exit();
    }

    $conn->close();
}
