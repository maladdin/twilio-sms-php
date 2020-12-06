<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsNotifier
{
    private const TWILIO_ID = '<your-twilio-account-id>';
    private const TWILIO_TOKEN = '<your-twilio-token>';
    private const TWILIO_PHONE_NUMBER = '<your-twilio-number>';
    private const TWILIO_ORDER_MESSAGING_SERVICE_ID = '<your-twilio-messaging-service-sid>';
    private const TWILIO_CUSTOMERS_NOTIFICATION_ID = '<your-notify-sid>';

    private Client $twilioClient;

    public function __construct()
    {
        $this->twilioClient = new Client(
            self::TWILIO_ID,
            self::TWILIO_TOKEN
        );
    }

    public function sendOrderConfirmationSms(
        string $phoneNumber,
        string $messageBody
    ): string
    {
        $message = $this->twilioClient->messages->create($phoneNumber,
            [
                "body" => $messageBody,
                "from" => self::TWILIO_PHONE_NUMBER
            ]);

        return $message->sid;
    }

    public function sendBulkSms(array $phoneNumbers, string $messageBody): string
    {
        foreach ($phoneNumbers as $phoneNumber) {
            $message =
                $this->twilioClient->messages->create($phoneNumber,
                    [
                        "body" => $messageBody,
                        "from" => self::TWILIO_ORDER_MESSAGING_SERVICE_ID
                    ]);

            echo $message->sid . PHP_EOL;

        }

        return "Message delivery in progress";
    }


    public function notify(array $phoneNumbers, string $messageBody): string
    {
        $bindings = array_map(function ($phoneNumber) {
            return json_encode([
                'binding_type' => 'sms',
                'address' => $phoneNumber
            ]);
        }, $phoneNumbers);

        $binding = $this->twilioClient
            ->notify->services(self::TWILIO_CUSTOMERS_NOTIFICATION_ID)
            ->notifications->create([
                "toBinding" => $bindings,
                'body' => $messageBody
            ]);

        return $binding->sid;
    }

}