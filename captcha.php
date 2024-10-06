<?php
session_start();

$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$captcha_text = substr(str_shuffle($characters), 0, 6);
$_SESSION['captcha'] = $captcha_text;

$width = 150;
$height = 50;

$image = imagecreate($width, $height);

// Colors
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$border_color = imagecolorallocate($image, 0, 0, 0); // Black border

// Create some colors for text
$colors = [
    imagecolorallocate($image, 255, 0, 0),   // Red
    imagecolorallocate($image, 0, 102, 0),   // Green
    imagecolorallocate($image, 0, 0, 255),    // Blue
    imagecolorallocate($image, 0, 0, 0)    // Black
];
// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $background_color);

// Add border
imagerectangle($image, 0, 0, $width-1, $height-1, $border_color);

// Add random dots for noise
for ($i = 0; $i < 1000; $i++) {
    $noise_color = $colors[array_rand($colors)];
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
}

// Add the CAPTCHA text
$font_size = 20;
$x = 10;
$y = 35;
for ($i = 0; $i < strlen($captcha_text); $i++) {
    $text_color = $colors[array_rand($colors)];
    imagettftext($image, $font_size, rand(-15, 15), $x + ($i * 20), $y, $text_color, __DIR__ . '/arial.ttf', $captcha_text[$i]);
}

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
