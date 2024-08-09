<?php 

namespace App\Tts;
use CurlFile;

class Cloudflare{

    private $api_key;
    private $barier_token;
    function __construct($api_key,$barier_token)
    {
        $this->api_key = $api_key;
        $this->barier_token = $barier_token;
    }

	public  function speechToText($audioPath){
        
        if(is_dir($audioPath) || !file_exists($audioPath))
        return false;
        $url = 'https://api.cloudflare.com/client/v4/accounts/'.  $this->api_key . '/ai/run/@cf/openai/whisper';
//5f7b33fb9422bfeb75314ad3ba51cc1b
        $ch = curl_init($url);

        $headers = [
            'Authorization: Bearer ' . $this->barier_token,
            'Content-Type: multipart/form-data'
        ];  //mYhtGfnUWemnVdjhuUuiS7HZUkZfPbozXLsGu1n1

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($audioPath));
        //curl_setopt($ch, CURLOPT_INFILE, $stream);
        //curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $ret =  json_decode($response, true);
      

        if(isset($ret['success']) &&  $ret['success'] == true)
            return ['text'=>$ret['result']['text'],'confidence'=>null];

        return false;		
    }

}    