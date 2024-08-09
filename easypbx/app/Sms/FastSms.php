<?php 

namespace App\Sms;

use App\Sms\SmsService;
use  App\Enums\SmsStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FastSms implements SmsService{

    protected $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    public function send($to, $body, $from = null):array{
        $url  = "https://fastsmsportal.com/smsapi?apiKey={$this->options['apikey']}&senderId={$this->options['senderId']}&mobileNo={$to}&message={$body}";

        $res = Http::get( $url );
        Log::info( $res );
        
        return $this->prepareResponse(json_decode( $res, true ));
    }

    public function prepareResponse($smsResponse):array{
        $response = [];

        $response['success'] = $smsResponse['status'] == 'Success' ? true : false;
        $response['status'] = $this->getStatus($smsResponse['status']);
        $response['trxid'] = $smsResponse['messageid'];
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