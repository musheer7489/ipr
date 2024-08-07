<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $captcha = $_POST['captcha'];

    if ($captcha == $_SESSION['captcha']) {
        echo 'success';
    } else {
        echo 'fail';
    }
}
?>
