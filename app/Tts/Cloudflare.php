<?php 

namespace App\Tts;
use CurlFile;
use Illuminate\Support\Facades\Http;

class Cloudflare{

    private $api_key;
    private $barier_token;
    function __construct($api_key,$barier_token)
    {
        $this->api_key = $api_key;
        $this->barier_token = $barier_token;
    }

    public function llm($prompt, $instructions, $model = '@cf/deepseek-ai/deepseek-r1-distill-qwen-32b', $lang = 'en'){
        $url = sprintf("https://api.cloudflare.com/client/v4/accounts/%s/ai/run/%s", $this->api_key, $model);
        info($url);
        
      //  $instructions = "You are a friendly voice assistant  for Infosoftbd Solution .Please answer in short for following prompt. Infosoftbd Solutions is a private Software development firm In Bangladesh. We help our clients achieve their goals by helping them implement and use new technology, streamline their processes and workflows, improve the customer experience, enhance their marketing strategy, increase sales from a global perspective, simplify operations by improving efficiency, decrease costs by automating workflows";
       
        $content = [
            'messages' => [
                ['role' => 'system', 'content' => $instructions],
                ['role' => 'user', 'content' => $prompt]
            ]
        ];

        info(json_encode($content));

        $response = Http::withToken($this->barier_token)
            ->post($url, $content);

        $data = $response->json();
      /*   $headers = [
            'Authorization: Bearer ' . $this->barier_token];
        $ch = curl_init($url);    
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($content));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $data =  json_decode($response, true);
 */
        info("cloudflare response");
        info($data);
        return $data['result']['response'] ?? false;
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