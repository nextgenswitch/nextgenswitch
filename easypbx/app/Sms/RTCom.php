<?php 

namespace App\Sms;

use App\Sms\SmsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use  App\Enums\SmsStatusEnum;

class RTCom implements SmsService{

    protected $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    public function send($to, $body, $from = null):array{
        $url = 'https://api.rtcom.xyz/onetomany';

        $playload = [
            "acode" => $this->options['acode'],
            "api_key" => $this->options['apikey'],
            "senderid" => $this->options['senderId'],
            "type" => "text",
            "msg" => $body,
            "contacts" => $to,
            "transactionType" =>"P",
            "contentID" =>""
        ];

        $res = Http::post($url, $playload);
        Log::info( $res );
        
        return $this->prepareResponse(json_decode( $res, true ));
    }

    public function prepareResponse($smsResponse):array{
        $response = [];

        $response['success'] = $smsResponse['response']['message'] == 'Success' ? true : false;
        $response['status'] = $this->getStatus($smsResponse['response']['message']);
        $response['trxid'] = $smsResponse['info']['requestID'];
        $response['res_data'] = $smsResponse;

        return $response;
    }

    private function getStatus($status){
        switch($status){
            case 'Success':
                return SmsStatusEnum::Sent;
                break;
            
            default:
                return SmsStatusEnum::Failed;
        }
    }
}