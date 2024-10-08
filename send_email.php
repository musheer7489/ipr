<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    // Retrieve user_id from session
    // if (isset($_SESSION['user_id'])) {
    //     $user_id = $_SESSION['user_id'];
    // } else {
    //     echo 'User ID not found in session.';
    //     exit;
    // }

    // PHPMailer setup
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                           // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = 'rajpoot8445@gmail.com'; // SMTP username
        $mail->Password   = 'nqbslisopcmvzmqn';       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                   // TCP port to connect to

        //Recipients
        $mail->setFrom('ipr@noreply.com', 'Institute for Plasma Research');
        $mail->addAddress($email, 'Recipient'); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'You have Seccessfully Registered';
        $mail->Body = '<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 5px;
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .details {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #555555;
        }
    </style>
    <title>Complete Your Registration Process</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Complete Your Registration Process</h2>
        </div>
        <div class="content">
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>You have successfully registered! To complete your registration, please follow these steps:</p>
            <ol>
                <li>Fill in your remaining details by accessing your account.</li>
                <li>Make the required payment through our secure payment portal.</li>
            </ol>
            <div class="details">
                <p><strong>Here are your registration details for reference:</strong></p>
                <p>Full Name: ' . htmlspecialchars($name) . '</p>
                <p>Email ID: ' . htmlspecialchars($email) . '</p>
                <p>Mobile Number: ' . htmlspecialchars($mobile) . '</p>
                <p>Date of Birth: ' . htmlspecialchars($dob) . '</p>
            </div>
            <p>If you have any questions or need assistance, please feel free to contact us. Please note that this is an automated email; do not reply to this message. Instead, direct your inquiries to ipr@digialm.in.</p>
            <p>Thank you for your prompt attention to this matter.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p>Hreetesh Pandey</p>
            <p>HR Head</p>
            <p>hreetesh@ipr.in</p>
            <p>Institute for Plasma Research</p>
        </div>
    </div>
</body>
</html>';
        $mail->AltBody = "Name: $name\nDate of Birth: $dob\nMobile Number: $mobile";

        $mail->send();
        $emailResponse = array('status' => 'success', 'message' => 'Email sent successfully.');
    } catch (Exception $e) {
        $emailResponse = array('status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
} else {
    $emailResponse = array('status' => 'error', 'message' => 'Invalid request method.');
}
echo json_encode($emailResponse);
