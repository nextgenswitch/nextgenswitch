<?php

namespace App\Sms;

interface SmsService
{
    /**
     * Send an SMS message.
     *
     * @param string $to Recipient phone number.
     * @param string $body Message body.
     * @param string|null $from Sender phone number. Default is null.
     * @return array
     */
    public function send($to, $body, $from = null): array;

    /**
     * Prepare the response from the SMS service.
     *
     * @param mixed $smsResponse The response from the SMS service.
     * @return array
     * Example:
     * [
     *     'success' => bool,
     *     'status' => 'sent'|'pending',
     *     'trxid' => string,
     *     'res_data' => array|object
     * ]
     */
    public function prepareResponse($smsResponse): array;
}