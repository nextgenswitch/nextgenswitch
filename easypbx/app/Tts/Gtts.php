<?php
namespace App\Tts;
use Illuminate\Support\Facades\Http;

class Gtts{
    private $api_url;
    function __construct($url){
        $this->api_url = $url;
    }
    public function synthesize($text,$lang = 'en-US',$options = []){
        if(empty($text)) return;
        if($lang)
          $lang = explode('-',$lang)[0]; 
        $response = Http::get($this->api_url, [
            'text' => $text,
            'lang' => $lang,
        ]);
        return $response->json('file');

        
    }
}