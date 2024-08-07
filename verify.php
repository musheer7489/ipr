<?php
require 'vendor/autoload.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

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
    header("Location: preview.php?order_id=$order_id&status=successful");
} else {
    // Log the error for debugging
    error_log("Payment verification failed: $error");
    header("Location: preview.php?order_id=$order_id&status=failed&error=" . urlencode($error));
}
exit();
?>
