<?php

namespace App\Tts;

class WitAi
{

    private $sttBaseUrl = 'https://api.wit.ai/dictation';
    private $ttsBaseUrl = 'https://api.wit.ai/synthesize';
    private $token;
    private $api_version;
    private $headers = [];

    public function __construct($token, $api_version)
    {
        $this->token       = $token;
        $this->api_version = $api_version;
        $this->buildSttUrl();
    }



    function parseMultipleJsonObjects(string $jsonString): array
    {
        $results = [];
        $length = strlen($jsonString);
        $braceCount = 0;
        $buffer = '';

        for ($i = 0; $i < $length; $i++) {
            $char = $jsonString[$i];

            if ($char === '{') {
                $braceCount++;
            }

            if ($braceCount > 0) {
                $buffer .= $char;
            }

            if ($char === '}') {
                $braceCount--;

                if ($braceCount === 0) {
                    // Attempt to decode full JSON object
                    $decoded = json_decode($buffer, true);
                    if ($decoded !== null) {
                        $results[] = $decoded;
                    } else {
                        // Optionally log or handle parse error
                        // echo "Failed to decode JSON: \n$buffer\n";
                    }
                    $buffer = '';
                }
            }
        }

        return $results;
    }


    public function speechToText($audioPath)
    {

        if (is_dir($audioPath) || ! file_exists($audioPath)) {
            return false;
        }
        //info($audioPath);

        $params = [
            'v' => $this->api_version,

        ];
        $queryString = http_build_query($params);

        $request = curl_init($this->sttBaseUrl . '?' . $queryString);
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($request, CURLOPT_POSTFIELDS, file_get_contents($audioPath));
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->prepareHeaders(0));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($request);

        if (curl_errno($request)) {
            $error_msg = curl_error($request);
            // info( $error_msg );
            return false;
        }

        $resArr = $this->parseMultipleJsonObjects($response);
        // info("WitAi response: ");
        // info($resArr);
        $text = "";
        foreach ($resArr as $element) {
            // info("print single element");
            // info($element);

            if (isset($element['type']) && $element['type'] == 'FINAL_TRANSCRIPTION')
                $text .= $element['text'] . " ";
        }

        info("Final text: " . $text);
        if (!empty($text)) {
            return  [
                'text'       => $text,
                'confidence' => ""
            ];
        }


        // $ret = json_decode($response, true);

        // $text = "";
        // if (!isset($ret['text'])) {
        //     info("response multiple json objects");
        //     info($this->parseMultipleJsonObjects($response));


        // $objects = preg_split("/(^{|}\s*{|}$)/", $response);
        //info($objects);
        //$json = array_pop($objects);
        //$json = end($objects);
        // info("print object");
        // info($objects);
        // foreach ($objects as $obj) {
        //     info("print object element");
        //     info($obj);
        //     $json = "{" . $obj . "}";
        //     $parsed = json_decode($json, true);
        //     if (isset($parsed['type']) && $parsed['type'] == 'FINAL_TRANSCRIPTION')
        //         $text .= $parsed['text'] . " ";
        // }

        // $json = "{" . $json . "}";

        //$ret = json_decode( $json, true );

        // }
        // info("final text " . $text);



        // info("after json decode");
        //info($ret);
        // if (isset($ret['text'])) {
        //     return ['text' => $ret['text'], 'confidence' => isset($ret['speech']['confidence']) ? (string) $ret['speech']['confidence'] : ""];
        // }

        return false;
    }


    public function textToSpeech($text, $model = "Rebecca", $language = 'en')
    {

        $full_path = $this->outputPath($text);

        if (strlen($text) > 256)
            $text = substr($text, 0, 256);

        if (file_exists($full_path)) {
            return ['path' => $full_path, 'cache' => true];
        }


        $playload = [
            'q'     => substr($text, 0, 256),
            'voice' => $model,
            'style' => 'soft',
            'lang'  => $language,
        ];

        $request = curl_init($this->ttsBaseUrl);
        $fp      = fopen($full_path, 'w+');
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($playload));
        curl_setopt($request, CURLOPT_HTTPHEADER, $this->prepareHeaders());
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_FILE, $fp);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($request);
        fclose($fp);
        if (is_file($full_path)) {
            return ['path' => $full_path, 'cache' => false];
        }

        return false;
    }

    private function outputPath($text, $type = 'wav')
    {

        $storage_path = storage_path(config('enums.tts_storage_path') . 'witai');
        @mkdir($storage_path, 0777, true);

        $filename = md5($text) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }

    private function prepareHeaders($isTts = 1): array
    {
        $this->headers[] = 'Authorization: Bearer ' . $this->token;
        $this->headers[] = $isTts ? 'Content-Type: application/json' : 'Content-Type: audio/wav';
        $this->headers[] = $isTts ? 'Accept: audio/wav' : 'Accept: application/vnd.wit.' . $this->api_version . '+json';
        // if($isTts)  $this->headers[] =  'Content-Type: application/json';
        return $this->headers;
    }

    private function buildSttUrl(): void
    {
        $this->sttBaseUrl = $this->sttBaseUrl . '?v=' . $this->api_version;
    }
}
