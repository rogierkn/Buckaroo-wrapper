<?php
use Rogierkn\BuckarooWrapper\API;

require '../vendor/autoload.php';

$api = new API(
    require 'config.php'
);

$response = $api->setTestMode(true)
    ->verify(
        API::IDEAL,
        $_POST['BRQ_AMOUNT'],
        $_POST['BRQ_INVOICENUMBER']
    );

if ($response->isSuccessful()) {
    echo "Payment received";
} elseif ($response->isPending()) {
    echo "Waiting for verification";
} elseif ($response->isCancelled()) {
    echo "Payment cancelled";
}