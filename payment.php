<?php
require 'vendor/autoload.php';
use Razorpay\Api\Api;

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
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch user category from the database
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
// Retrieve user_id from session
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM applicants WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    $category = $user['category'];
    $name = $user['full_name'];
    $email = $user['email'];
    $contact = $user['mobile_number'];
    $gender = $user['gender'];
    $pwd = $user['pwd'];
    $amount = ($category == 'SC' || $category == 'ST' || $gender == 'Female' || $pwd == 'yes') ? 50 : 100;

    // Convert amount to paise
    $amount_in_paise = $amount * 100;

    // Create an order
    $orderData = [
        'receipt'         => 'rcptid_11',
        'amount'          => $amount_in_paise,
        'currency'        => 'INR',
        'payment_capture' => 1 // auto capture
    ];

    $razorpayOrder = $api->order->create($orderData);

    $orderId = $razorpayOrder['id'];
    $displayAmount = $amount;

    // Check if an order exists for the user
    $stmt = $pdo->prepare('SELECT id FROM payments WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $existingOrder = $stmt->fetch();

    if ($existingOrder) {
        // Update the existing order with the new order_id
        $stmt = $pdo->prepare('UPDATE payments SET order_id = ?, amount = ? WHERE user_id = ?');
        $stmt->execute([$orderId, $amount_in_paise, $user_id]);
    } else {
        // Store the new order in the database
        $stmt = $pdo->prepare('INSERT INTO payments (user_id, order_id, amount, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $orderId, $amount_in_paise, 'created']);
    }

    // Pass these details to your front-end
    $data = [
        "key"               => $api_key,
        "amount"            => $amount_in_paise,
        "name"              => "Institute for Plasma Research",
        "description"       => "Pay Application Fee to Complete Form",
        "image"             => "ic.jpg",
        "prefill"           => [
            "name"              => $name,
            "email"             => $email,
            "contact"           => $contact,
        ],
        "notes"             => [
            "address"           => "Bhat, Near Indira Bridge, Gandhinagar 382 428 (India)",
            "merchant_order_id" => "12312321",
        ],
        "theme"             => [
            "color"             => "#F37254"
        ],
        "order_id"          => $orderId,
    ];
} else {
    die('User not found.');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>IPR | Payment</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #f0f0f0;
        }
        .payment-container {
            text-align: center;
        }
       
    </style>
    
</head>
<body>
<div class="payment-container">
    <form action="verify.php" method="POST" id="payment-form">
        <script
            id="razorpay-script"
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="<?php echo $data['key']; ?>"
            data-amount="<?php echo $data['amount']; ?>"
            data-currency="INR"
            data-order_id="<?php echo $data['order_id']; ?>"
            data-buttontext="Please Do Not Back or Refresh Your Page"
            data-name="<?php echo $data['name']; ?>"
            data-description="<?php echo $data['description']; ?>"
            data-image="<?php echo $data['image']; ?>"
            data-prefill.name="<?php echo $data['prefill']['name']; ?>"
            data-prefill.email="<?php echo $data['prefill']['email']; ?>"
            data-prefill.contact="<?php echo $data['prefill']['contact']; ?>"
            data-theme.color="<?php echo $data['theme']['color']; ?>"
            class="razorpay-button"
        ></script>
        <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

    </form>
</div>
<script>
        // Style Razorpay button
        var razorpayButton = document.querySelector('.razorpay-payment-button');
            if (razorpayButton) {
                razorpayButton.style.padding = "15px 30px";
                razorpayButton.style.fontSize = "2rem";
                razorpayButton.style.color = "#fff";
                razorpayButton.style.backgroundColor = "#f37254";
                razorpayButton.style.border = "none";
                razorpayButton.style.borderRadius = "5px";
                razorpayButton.style.cursor = "pointer";
            }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.querySelector('.razorpay-payment-button').click();
            }, 100); // 1000 milliseconds = 1 second
        });
    </script>
</body>
</html>
