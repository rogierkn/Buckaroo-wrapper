<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Rogierkn\BuckarooWrapper\API;

require '../vendor/autoload.php';


$api = new API(
    require 'config.php'
);

// iDeal payment
$response = $api->setTestMode(true)
    ->pay(
        API::IDEAL,
        $_POST['amount'],
        $_POST['transactionId'],
        "http://example.com/example/return.php",
        "http://example.com/example/return_cancel.php"
    )->redirect();