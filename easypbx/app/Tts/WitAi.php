<?php

namespace App\Tts;

class WitAi {

    private $sttBaseUrl = 'https://api.wit.ai/speech';
    private $ttsBaseUrl = 'https://api.wit.ai/synthesize';
    private $token;
    private $api_version;
    private $headers = [];

    public function __construct( $token, $api_version ) {
        $this->token       = $token;
        $this->api_version = $api_version;
        $this->buildSttUrl();
    }

    public function speechToText( $audioPath ) {

        if ( is_dir( $audioPath ) || ! file_exists( $audioPath ) ) {
            return false;
        }

        $request = curl_init( $this->sttBaseUrl );
        curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt( $request, CURLOPT_POSTFIELDS, file_get_contents( $audioPath ) );
        curl_setopt( $request, CURLOPT_HTTPHEADER, $this->prepareHeaders( 0 ) );
        curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec( $request );

        if ( curl_errno( $request ) ) {
            $error_msg = curl_error( $request );
            info( $error_msg );
            return false;
        }


        //info( $response);
        $ret = json_decode( $response, true );
        //info($ret);
        if ( isset( $ret['text'] ) ) {
            return ['text' => $ret['text'], 'confidence' => isset( $ret['speech']['confidence'] ) ? (string) $ret['speech']['confidence'] : ""];
        }

        return false;
    }

    public function textToSpeech( $text, $model = "Rebecca", $language = 'en' ) {

        $full_path = $this->outputPath( $text );

        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }

        $playload = [
            'q'     => $text,
            'voice' => $model,
            'style' => 'soft',
            'lang'  => $language,
        ];

        $request = curl_init( $this->ttsBaseUrl );
        $fp      = fopen( $full_path, 'w+' );
        curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt( $request, CURLOPT_POSTFIELDS, json_encode( $playload ) );
        curl_setopt( $request, CURLOPT_HTTPHEADER, $this->prepareHeaders() );
        curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $request, CURLOPT_FILE, $fp );
        curl_setopt( $request, CURLOPT_FOLLOWLOCATION, true );

        $response = curl_exec( $request );
        fclose( $fp );
        if ( is_file( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>false];
        }

        return false;
        
    }

    private function outputPath( $text, $type = 'wav' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'witai' );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }

    private function prepareHeaders( $isTts = 1 ): array {
        $this->headers[] = 'Authorization: Bearer ' . $this->token;
        $this->headers[] = $isTts ? 'Content-Type: application/json' : 'Content-Type: audio/wav';
        $this->headers[] = $isTts ? 'Accept: audio/wav' : 'Accept: application/vnd.wit.' . $this->api_version . '+json';

        return $this->headers;
    }

    private function buildSttUrl(): void {
        $this->sttBaseUrl = $this->sttBaseUrl . '?v=' . $this->api_version;
    }

}
