<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Check if success message exists in session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

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
    $dob = $row['dob'];
    $mobileNumber = $row['mobile_number'];
    $email = $row['email'];
    $category = $row['category'];
    $gender = $row['gender'];
    $pwd = $row['pwd'];
    $highest_qualification = $row['highest_qualification'];
    $university = $row['university'];
    $year_of_passing = $row['year_of_passing'];
    $post = $row['post'];
    $eligibility = $row['eligibility'];
    $exam_center = $row['exam_center'];
    $address = $row['address'];
    $is_submitted = $row['is_submitted'];
    $mothers_name = $row['mothers_name'];
    $fathers_name = $row['fathers_name'];
    $nationality = $row['nationality'];
    $is_ex_serviceman = $row['is_ex_serviceman'];
    $is_religious_minority = $row['is_religious_minority'];
    $is_central_govt_employee = $row['is_central_govt_employee'];
    $marital_status = $row['marital_status'];
    $degree = $row['degree'];
    $institution_name = $row['institution_name'];
    $board = $row['board'];
    $year_of_graduation = $row['year_of_graduation'];
    $city = $row['city'];
    $state = $row['state'];
    $country = $row['country'];
    $pin_code = $row['pin_code'];
    $photoPath = $row['photo_path'];
    $signaturePath = $row['signature_path'];
} else {
    // Redirect back or show an error message if user data does not exist
    // header("Location: form.php");
    // exit();
    $error_message = "User data not found.";
}

// Fetch the payment details
$stmt = $conn->prepare('SELECT * FROM payments WHERE user_id = ?');
$stmt->bind_param('s', $user_id);
$stmt->execute();
$payResult = $stmt->get_result();
$payment = $payResult->fetch_assoc();
if ($payment) {
    $status = $payment['status'];
    $amount = $payment['amount'] / 100; // Convert amount from paise to rupees
    $payment_id = $payment['payment_id'];
    $created_at = $payment['created_at']; // Assuming there's a 'created_at' column in payments table
} else {
    $error_message = "Payment Not Completed.";
}

$stmt->close();

// Close database connection
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="ic.jpg">
    <title>User Data Preview</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .photo img {
            max-width: 100px;
            max-height: 100px;
        }

        .bar_code {
            width: 200px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <a class="btn btn-danger mb-3" href="logout.php">Logout</a>
        <h2 class="text-center">Application Preview</h2>
        <?php if ($success_message) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <table class="table table-bordered" id="printTable">
            <tbody>
                <tr>
                    <td colspan="4"><img src="ipr-logo.png" alt="ipr-logo"></td>
                </tr>
                <?php if (!$payment) {
                    echo '<tr>';
                    echo  '<td colspan="2">Click on Pay Now to Complete Application</td>';
                    echo    '<th colspan="2"><a href="payment.php" class="btn btn-success">Pay Now</a></th>';
                    echo '</tr>';
                } ?>
                <tr>
                    <th>Application Number</th>
                    <td><?php echo $applicationNumber; ?></td>
                    <td colspan="2"><img src="generate_barcode.php" alt="bar code" class="bar_code"></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Personal Details</strong></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $fullName; ?></td>
                    <th>Date of Birth</th>
                    <td><?php echo $dob; ?></td>
                </tr>
                <tr>
                    <th>Mobile Number</th>
                    <td><?php echo $mobileNumber; ?></td>
                    <th>Email</th>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <th>Mother's Name</th>
                    <td><?php echo $mothers_name; ?></td>
                    <th>Father's Name</th>
                    <td><?php echo $fathers_name; ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo $gender; ?></td>
                    <th>Category</th>
                    <td><?php echo $category; ?></td>
                </tr>
                <tr>
                    <th>Marrital Status</th>
                    <td><?php echo $marital_status ?></td>
                    <th>Are you a PWD candidate?</th>
                    <td><?php echo $pwd; ?></td>

                </tr>
                <tr>
                    <th colspan="2">Are you an ex-serviceman?</th>
                    <td colspan="2"><?php echo $is_ex_serviceman; ?></td>
                </tr>
                <tr>
                    <th colspan="2">Do you belong to a religious minority?</th>
                    <td colspan="2"><?php echo $is_religious_minority; ?></td>
                </tr>
                <tr>
                    <th colspan="2">Are you an employee of the central government?</th>
                    <td colspan="2"><?php echo $is_central_govt_employee; ?></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Educational Details</strong></td>
                </tr>
                <tr>
                    <th colspan="4" class="table-secondary">HighSchool Details</th>
                </tr>
                <tr>
                    <th>Name of Course</th>
                    <td><?php echo $degree ?></td>
                    <th>Passing Year</th>
                    <td><?php echo $year_of_graduation ?></td>
                </tr>
                <tr>
                    <th colspan="2">School/College Name</th>
                    <td colspan="2"><?php echo $institution_name ?></td>
                </tr>
                <tr>
                    <th colspan="2">Board/University Name</th>
                    <td colspan="2"><?php echo $board ?></td>
                </tr>
                <tr>
                    <th colspan="4" class="table-secondary">Highest Qualification Detais</th>
                </tr>
                <tr>
                    <th>Highest Qualificaton</th>
                    <td><?php echo $highest_qualification ?></td>
                    <th>Passing Year</th>
                    <td><?php echo $year_of_passing ?></td>
                </tr>
                <tr>
                    <th colspan="2">Board/University Name</th>
                    <td colspan="2"><?php echo $university ?></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Post Details</strong></td>
                </tr>
                <tr>
                    <th>Post Applied For</th>
                    <td><?php echo $post ?></td>
                    <th>Choice of Examination Center</th>
                    <td><?php echo $exam_center ?></td>
                </tr>
                <tr>
                    <th colspan="2">Eligibility</th>
                    <td colspan="2"><?php echo $eligibility ?></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Address</strong></td>
                </tr>
                <tr>
                    <th colspan="2">House No/Ward No/Village/Town</th>
                    <td colspan="2"><?php echo $address ?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $city ?></td>
                    <th>State</th>
                    <td><?php echo $state ?></td>
                </tr>
                <tr>
                    <th>Pin Code</th>
                    <td><?php echo $pin_code ?></td>
                    <th>Nationality</th>
                    <td><?php echo $country ?></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Photo & Signature</strong></td>
                </tr>
                <tr>
                    <th colspan="2">Photo</th>
                    <td colspan="2" class="photo"><img src="<?php echo $photoPath; ?>" alt="Photo"></td>
                </tr>
                <tr>
                    <th colspan="2">Signature</th>
                    <td colspan="2" class="photo"><img src="<?php echo $signaturePath; ?>" alt="Signature"></td>
                </tr>
                <tr class="bg-dark text-white">
                    <td colspan="4"><strong>Payment Details</strong></td>
                </tr>
                <?php if ($payment) : ?>
                    <?php if ($status === 'successful') : ?>
                        <tr>
                            <th>Payments Status</th>
                            <td><?php echo ($status); ?></td>
                            <th>Payment ID</th>
                            <td><?php echo ($payment_id); ?></td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td>₹<?php echo ($amount); ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <th>Payments Status</th>
                            <td>Not Paid</td>
                            <td>Click on Pay Now to Complete Application</td>
                            <th><a href="payment.php" class="btn btn-success">Pay Now</a></th>
                        </tr>

                    <?php endif; ?>
                <?php else : ?>
                    <tr>
                        <td>Click on Pay Now to Complete Application</td>
                        <th><a href="payment.php" class="btn btn-success">Pay Now</a></th>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
        if($payment){
            if($status=='successful'){
                echo '<button class="print-button btn btn-info" onclick="printTable()">Print Application Form</button>';
            }
        }
        ?>


    </div>
    <script>
    function printTable() {
            var divToPrint = document.getElementById('printTable');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><title>Print</title>');
            newWin.document.write('<style>table {width: 100%; border-collapse: collapse;} table, th, td {border: 1px solid black;} th, td {padding: 10px; text-align: left;}</style>');
            newWin.document.write('<style>.photo img {max-width: 100px;max-height: 100px;}.bar_code {width: 200px;}</style>');
            newWin.document.write('</head><body onload="window.print()">');
            newWin.document.write(divToPrint.outerHTML);
            newWin.document.write('</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},100);
        }
    </script>
</body>

</html>