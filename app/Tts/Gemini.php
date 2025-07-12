<?php

namespace App\Tts;

use Illuminate\Support\Facades\Http;
use CurlFile;

class Gemini
{
    private $api_key;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';


    function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    public function llm($prompt, $instructions, $model = null, $lang = null)
    {
        // info('Log from Gemini class');
        // info($prompt);

        $model = $model ? $model : 'gemini-2.0-flash:generateContent';
        $this->baseUrl .= $model;


        try {
            $parts = [];

            // Add instructions if provided
            if (!empty($instructions)) {
                $parts[] = ['text' => $instructions];
            }

            // Add the main prompt
            $parts[] = ['text' => $prompt];

            $response = Http::post("{$this->baseUrl}?key={$this->api_key}", [
                'contents' => [[
                    'parts' => $parts
                ]]
            ]);

            info('Gemini Response:');
            info($response);

            return $response->successful()
                ? $response->json()['candidates'][0]['content']['parts'][0]['text']
                : $response->body();
        } catch (\Exception $e) {
            return false;
        }
    }
}
