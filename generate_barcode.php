<?php
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

// Initialize the generator
$generator = new BarcodeGeneratorPNG();

// Generate the barcode
$barcode = $generator->getBarcode('100125124', $generator::TYPE_CODE_128);

// Set the content type header - this is important for displaying the image
header('Content-Type: image/png');

// Output the barcode image
echo $barcode;
?>
