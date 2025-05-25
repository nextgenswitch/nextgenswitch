<?php 

namespace App\Http\Traits;

trait WitAiTrait{

    public  function speechToText($audioPath){

        if(is_dir($audioPath) || !file_exists($audioPath))
            return false;
        $request = curl_init('https://api.wit.ai/speech');

        $headers[] = 'Authorization: Bearer ' . config('witai.token');
        $headers[] = 'Content-Type: audio/wav';
        $headers[] = 'Accept: application/vnd.wit.' . config('witai.api_version') .'+json';

        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($request, CURLOPT_POSTFIELDS, file_get_contents($audioPath));
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($request);

        $ret =  json_decode($response, true);
        //dd($ret);
        if(isset($ret['text']) && isset($ret['speech']['confidence']))
            return ['text'=>$ret['text'],'confidence'=>(string) $ret['speech']['confidence']];
        return false;
    }


    public function getIntent($text){



        $url = 'https://api.wit.ai/message';
        $params = [
            'v' => config('witai.api_key'),
            'q' => $text,
        ];

        $queryString = http_build_query($params);


        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $headers = [
            'Authorization: Bearer ' . config('witai.token'),
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($ch);


        if (curl_errno($ch))
            return false;


        curl_close($ch);

        return json_decode($response, true);

    }
}
