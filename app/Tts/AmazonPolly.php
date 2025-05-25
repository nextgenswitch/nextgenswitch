<?php

namespace App\Tts;

use Aws\Polly\PollyClient;

class AmazonPolly {

    private $client;
    private $api_key;
    private $api_secret;
    private $version;
    private $region;
    private $http_verify;

    public function __construct( $api_key, $api_secret, $region, $version = 'latest', $http_verify = false ) {
        $this->api_key     = $api_key;
        $this->api_secret  = $api_secret;
        $this->region      = $region;
        $this->version     = $version;
        $this->http_verify = $http_verify;

        $this->setAwsClient();

    }

    private function setAwsClient() {
        $this->client = new PollyClient( [
            'credentials' => [
                'key'    => $this->api_key,
                'secret' => $this->api_secret,
            ],
            'version'     => $this->version,
            'region'      => $this->region,
            'http'        => [
                'verify' => $this->http_verify,
            ],
        ] );

    }

    public function textToSpeech( $text, $LanguageCode = 'en-US', $model = 'Ivy', $OutputFormat = 'mp3', $TextType = 'text' ) {

        $path = $this->outputPath( $text, $OutputFormat );

        if ( file_exists( $path ) ) {
            return ['path'=>$path,'cache'=>true];
        }

        $params = [
            'Text'         => $text,
            'LanguageCode' => $LanguageCode,
            'OutputFormat' => $OutputFormat,
            'TextType'     => $TextType,
            'VoiceId'      => $model,
        ];

        $voice = $this->client->synthesizeSpeech( $params );

        $voiceContent = $voice->get( 'AudioStream' )->getContents();

        file_put_contents( $path, $voiceContent );

        if ( is_file( $path ) ) {
            return ['path'=>$path,'cache'=>false];
        }

        return false;
    }

    public function speechToText( $audioPath ) {
    // not implemented
    }

    private function outputPath( $text, $type = 'wav' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'amazon' );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }

}
