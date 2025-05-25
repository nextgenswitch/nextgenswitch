<?php 

namespace App\Sms;

use App\Sms\SmsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use  App\Enums\SmsStatusEnum;

class Telegram implements SmsService{

    protected $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    public function send($to, $body, $from = null):array{
        $msg = urlencode($body);
        $url = "https://api.telegram.org/bot{$this->options['token']}/sendMessage?chat_id={$this->options['chatId']}&text={$msg}";
        Log::debug($url);
        $res = Http::get($url);
        Log::info( $res );
        
        return $this->prepareResponse(json_decode( $res, true ));
    }

    public function prepareResponse($smsResponse):array{
        $response = [];

        $response['success'] = $smsResponse['ok'];
        $response['status'] = $this->getStatus($smsResponse['ok']);
        $response['trxid'] = '';
        $response['res_data'] = $smsResponse;

        return $response;
    }

    private function getStatus($status){
        switch($status){
            case true:
                return SmsStatusEnum::Sent;
                break;

            default:
                return SmsStatusEnum::Failed;
        }
    }
}