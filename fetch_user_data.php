<?php
session_start();
include 'db_connection.php';

$response = array('status' => 'error', 'message' => 'Invalid request');

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Fetch user details from the database
    //$sql = "SELECT full_name, dob, mobile_number, email, highest_qualification, university, post, address, photo_path, signature_path FROM applicants WHERE id=?";
    $sql = "SELECT * FROM applicants WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    //$stmt->bind_result($full_name, $dob, $mobile_number, $email, $highestQualification, $university, $post, $address, $photo, $signature);
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
    if ($row>0) {
        $response = array(
            'status' => 'success',
            'data' => array(
                'id' => $row['id'],
                'full_name' => $row['full_name'],
                'dob' => $row['dob'],
                'mobile_number' => $row['mobile_number'],
                'email' => $row['email'],
                'category' => $row['category'],
                'gender' => $row['gender'],
                'pwd' => $row['pwd'],
                'highest_qualification' => $row['highest_qualification'],
                'university' => $row['university'],
                'address' => $row['address'],
                'post' => $row['post'],
                'eligibility' => $row['eligibility'],
                'exam_center' => $row['exam_center'],
                'photo_path' => $row['photo_path'],
                'signature_path' => $row['signature_path'],
                'mothers_name' => $row['mothers_name'],
                'fathers_name' => $row['fathers_name'],
                'nationality' => $row['nationality'],
                'is_ex_serviceman' => $row['is_ex_serviceman'],
                'is_religious_minority' => $row['is_religious_minority'],
                'is_central_govt_employee' => $row['is_central_govt_employee'],
                'marital_status' => $row['marital_status'],
                'degree' => $row['degree'],
                'institution_name' => $row['institution_name'],
                'board' => $row['board'],
                'year_of_graduation' => $row['year_of_graduation'],
                'year_of_passing' => $row['year_of_passing'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'pin_code' => $row['pin_code'],
            )
        );
    } else {
        $response = array('status' => 'error', 'message' => 'User not found.');
    }
    }
    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
