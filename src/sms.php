<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\TwilioSmsNotifier;

echo "it works";
$twilioService = new TwilioSmsNotifier();
$twilioService->sendBulkSms(
    [
        '<first-phone-number>',
        '<second-phone-number>',
        '<third-phone-number>',
    ],
    'Your order has been dispatched');

$twilioService->sendOrderConfirmationSms('<set-your-phone-number-here>', "Your order has been confirmed");


//$twilioService->notify(['<first-phone-number>','<second-phone-number>'], "Your order shipped");