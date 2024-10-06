<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'User not logged in'));
    exit();
}
$user_id = $_SESSION['user_id'];
include 'db_connection.php';
$response = array('status' => 'error', 'message' => 'Invalid request');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['step'];
    $user_id = $_SESSION['user_id']; // Get user_id from session

    switch ($step) {
        case 1:
            $fullName = $_POST['fullName'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];
            $mobileNumber = $_POST['mobileNumber'];
            $mothersName = $_POST['mothersName'];
            $fathersName = $_POST['fathersName'];
            $gender = $_POST['gender'];
            $category = $_POST['category'];
            $pwdCandidate = $_POST['pwdCandidate'];
            $exServiceman = $_POST['exServiceman'];
            $religiousMinority = $_POST['religiousMinority'];
            $centralGovtEmployee = $_POST['centralGovtEmployee'];
            $maritalStatus = $_POST['maritalStatus'];
            $sql = "UPDATE applicants SET full_name=?, dob=?, email=?, mobile_number=?,mothers_name=?,fathers_name =?,gender=?,category=?,pwd=?,is_ex_serviceman=?,is_religious_minority=?,is_central_govt_employee=?,marital_status=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssssi", $fullName, $dob, $email, $mobileNumber, $mothersName, $fathersName, $gender, $category, $pwdCandidate, $exServiceman, $religiousMinority,$centralGovtEmployee, $maritalStatus, $user_id);
            break;
        case 2:
            $course = $_POST['course'];
            $school = $_POST['school'];
            $board = $_POST['board'];
            $passingYear = $_POST['passingYear'];
            $highestQualification = $_POST['highestQualification'];
            $university = $_POST['university'];
            $year_of_passing = $_POST['year_of_passing'];
            $sql = "UPDATE applicants SET degree =?,institution_name =?,board =?,year_of_graduation =?, highest_qualification=?, university=?, year_of_passing=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi",$course, $school, $board, $passingYear, $highestQualification, $university, $year_of_passing, $user_id);
            break;
        case 3:
            $post = $_POST['post'];
            $eligibility = $_POST['eligibility'];
            $examCenter = $_POST['examCenter'];
            $sql = "UPDATE applicants SET post=?, eligibility =?,exam_center =? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $post, $eligibility, $examCenter, $user_id);
            break;
        case 4:
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $pinCode = $_POST['pinCode'];
            $country = $_POST['country'];
            $sql = "UPDATE applicants SET address=?, city=?, state=?,country=?,pin_code=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $address, $city, $state, $country, $pinCode, $user_id);
            break;
        case 5:
            // Handle file uploads
            $photo = $_POST['photo'];
            $signature = $_POST['signature'];
            $sql = "UPDATE applicants SET photo_path=?, signature_path=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $photo, $signature, $user_id);
            break;
    }

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Data saved successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to save data');
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
