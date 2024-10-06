<?php
session_start();
require 'vendor/autoload.php';
include 'config.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$api_key = 'rzp_test_64ZQLjjTKXqotI';
$api_secret = '3zqbJJaTOWsP4nkhBSqjD4uH';

$api = new Api($api_key, $api_secret);

// Database connection
$host = 'localhost';
$db = 'digialm';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
// Retrieve user_id from session
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM applicants WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
//Retrieve user information from database
if ($user) {
    $category = $user['category'];
    $name = $user['full_name'];
    $email = $user['email'];
    $contact = $user['mobile_number'];
    $gender = $user['gender'];
    $pwd = $user['pwd'];
    $amount = ($category == 'SC' || $category == 'ST' || $gender == 'Female' || $pwd == 'yes') ? 50 : 100;
}
else{
    die('User Not Found');
}

$success = true;
$error = "Payment Failed";
$payment_id = '';
$order_id = '';

if (!empty($_POST['razorpay_payment_id']) && !empty($_POST['order_id'])) {
    $payment_id = $_POST['razorpay_payment_id'];
    $order_id = $_POST['order_id'];

    try {
        $attributes = [
            'razorpay_order_id' => $order_id,
            'razorpay_payment_id' => $payment_id,
            'razorpay_signature' => $_POST['razorpay_signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);

        // Update the payment status to successful
        $stmt = $pdo->prepare('UPDATE payments SET payment_id = ?, status = ? WHERE order_id = ?');
        if ($stmt->execute([$payment_id, 'successful', $order_id])) {
            $success = true;
        } else {
            $success = false;
            $error = 'Failed to update payment status in the database.';
        }
    } catch (SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error: ' . $e->getMessage();

        // Update the payment status to failed
        $stmt = $pdo->prepare('UPDATE payments SET status = ? WHERE order_id = ?');
        $stmt->execute(['failed', $order_id]);
    } catch (Exception $e) {
        $success = false;
        $error = 'General Error: ' . $e->getMessage();
    }
} else {
    $success = false;
    $error = 'Payment details are missing.';
}

if ($success === true) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;  // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME; // SMTP username
        $mail->Password = SMTP_PASSWORD;  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Recipient and content settings
        $mail->setFrom(NOREPLY_EMAIL, COMPANY_NAME);
        $mail->addAddress($email, 'Recipient');  // Customer's email address

        // HTML email body
        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation - Thanks for Completing your Application!';

        $mail->Body = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .email-container {
                background-color: #ffffff;
                padding: 20px;
                margin: 30px auto;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                width: 600px;
            }
            .header {
                background-color: #4CAF50;
                color: white;
                text-align: center;
                padding: 10px 0;
                font-size: 22px;
                border-radius: 10px 10px 0 0;
            }
            .content {
                padding: 20px;
                font-size: 16px;
                color: #333;
            }
            .content p {
                line-height: 1.6;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                color: #888;
                font-size: 14px;
            }
            .footer a {
                color: #4CAF50;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                Payment Confirmation
            </div>
            <div class="content">
                <p>Dear <strong>'. $name.'</strong>,</p>
                <p>Thank You for Completing your Application! We are pleased to inform you that your payment has been successfully processed.</p>
                <p><strong>Payment Details:</strong></p>
                <ul>
                    <li><strong>Application Number: </strong> ' . $user_id . '</li>
                    <li><strong>Payment ID: </strong> ' . $payment_id . '</li>
                    <li><strong>Amount: </strong>' . $amount . '</li>
                    <li><strong>Date: </strong> ' . date("F j, Y") . '</li>
                </ul>
                <p>If you have any questions or need further assistance, feel free to contact our support team at <a href="mailto:'.SUPPORT_EMAIL.'">'.SUPPORT_EMAIL.'</a>.</p>
            </div>
            <div class="footer">
                <p>&copy; ' . date("Y") . ' '.COMPANY_NAME.'. All rights reserved.</p>
                <p><a href="'.COMPANY_URL.'">Visit our website</a> | <a href="'.COMPANY_URL.'/privacy">Privacy Policy</a></p>
            </div>
        </div>
    </body>
    </html>';

        // Send email
        $mail->send();
        echo 'Payment confirmation email sent successfully.';
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
    header("Location: preview.php?order_id=$order_id&status=successful");
} else {
    // Log the error for debugging
    error_log("Payment verification failed: $error");
    header("Location: preview.php?order_id=$order_id&status=failed&error=" . urlencode($error));
}
exit();