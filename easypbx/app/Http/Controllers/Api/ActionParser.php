<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Models\TtsProfile;
use App\Tts\Tts;
use GuzzleHttp;

class ActionParser {
    private $org_id;
    private $url;
    public static function parse( $org_id, $content ) {
        $parser         = new ActionParser;
        $parser->org_id = $org_id;

        return $parser->parseElements( $content );
    }

    public function parseElements( $elems ) {
        $actions = [];

        if ( is_array( $elems ) ) {

            foreach ( $elems as $elem ) {
                $actions = array_merge( $actions, $this->parseElement( $elem ) );
            }

        } else {
            $actions = array_merge( $actions, $this->parseElement( $elems ) );
        }

        return $actions;
    }

    public function sayVerb( $text, $options ) {
        $profile_id = isset( $options['profile'] ) ? $options['profile'] : null;

        $file  = Tts::synthesize( $text,$this->org_id, $profile_id,$options );
        if(!$file) return new VoiceResponse();

        $options['localfile'] = true;

        if ( $file ) {
            return VoiceResponse::genXmlElelment( 'play', $file, $options );
        }

    }

    public function parseElement( $elem ) {
        //if(empty($elem)) return [];
        $actions = [];
       // info()
        //info($elem);
        $tag     = strtolower( $elem->getName() );

        if ( $tag == 'response' ) {
            //Log::info($elem->asXml());
            //Log::info("got url in $tag " . (string) $elem->attributes()->url);
            if(!empty((string) $elem->attributes()->url)) $this->url = (string) $elem->attributes()->url;

            foreach ( $elem as $verb ) {
                $actions = array_merge( $actions, $this->parseElement( $verb ) );
            }

            return $actions;
        } elseif ( $tag == 'play' ) {
            $verb = ['verb' => $tag, 'file' => (string) $elem, 'loop' => 1, 'localfile' => false];

            foreach ( $elem->attributes() as $ak => $av ) {
                if ( $ak == 'localfile' ) {
                    $verb['localfile'] = ( (string) $av == 'true' ) ? true : false;
                }

                if ( $ak == 'loop' ) {$verb['loop'] = ( (int) $av <= 0 ) ? 1000 : (int) $av;}

            }

            $actions[] = $verb;
        } elseif ( $tag == 'gather' ) {
            $play = [];
            $verb = ['verb' => $tag, 'mode' => 0, 'numDigits' => 0, 'beep' => false, 'finishOnKey' => 35, 'action' => $this->url, 'method' => 'POST', 'actionOnEmptyResult' => false, 'timeout' => 10,'transcript'=>true, 'actions' => []];

            foreach ( $elem->attributes() as $ak => $av ) {
                if ( $ak == 'action' ) {
                    $verb['action'] = (string) $av;
                } elseif ( $ak == 'method' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['method'] = $av;
                } elseif ( $ak == 'timeout' ) {
                    $verb['timeout'] = (int) $av;
                } elseif ( $ak == 'speechTimeout' ) {
                    $verb['speechTimeout'] = (int) $av;
                } elseif ( $ak == 'numDigits' ) {
                    $verb['numDigits'] = (int) $av;
                } elseif ( $ak == 'finishOnKey' ) {
                    $verb['finishOnKey'] = ord( (string) $av );
                } elseif ( $ak == 'actionOnEmptyResult' ) {
                    $verb['actionOnEmptyResult'] = ( (string) $av == 'true' ) ? true : false;
                }elseif ( $ak == 'transcript' ) {
                    $verb['transcript'] = ( (string) $av == 'true' ) ? true : false;
                } elseif ( $ak == 'beep' ) {
                    $verb['beep'] = ( (string) $av == 'true' ) ? true : false;
                } elseif ( $ak == 'speechProfile' ) {
                    $verb['speechProfile'] = (string) $av;
                } elseif ( $ak == 'input' ) {
                    switch ( strtolower( (string) $av ) ) {
                    case "dtmf":
                        $verb['mode'] = 0;
                        break;
                    case "speech":
                        $verb['mode'] = 1;
                        break;
                    case "dtmf speech":
                        $verb['mode'] = 2;
                        break;
                    default:
                        $verb['mode'] = 0;
                        break;
                    }

                }

            }

            foreach ( $elem as $name => $child ) {
                if ( $name == 'play' || $name == 'say' ) {
                    $play = array_merge( $play, $this->parseElement( $child ) );
                }

            }

            if ( $verb['beep'] == true ) {
                $play = array_merge( $this->parseElements( VoiceResponse::genXmlElelment( 'play', storage_path( 'app/public/sounds/beep.wav' ), ['localfile' => "true"] ) ), $play );
            }

            $verb['actions'] = $play;
            $actions[]       = $verb;

        } elseif ( $tag == 'dial' ) {
            $play = [];
            $dial = ['verb' => $tag, "to" => (string) $elem, 'ringTone'=>true,'answerOnBridge' => false, 'hangupOnStar' => false, 'recordingTrack' => 0, 'timeLimit' => 4 * 60];
            foreach ( $elem->attributes() as $ak => $av ) {
                if ( $ak == 'channel' ) {
                    $dial['channel'] = (string) $av;
                } elseif ( $ak == 'channel_id' ) {
                    $dial['channel_id'] = (int) $av;
                } elseif ( $ak == 'answerOnBridge' ) {
                    $dial['answerOnBridge'] = ( (string) $av == 'true' ) ? true : false;
                } elseif ( $ak == 'action' ) {
                    $dial['action'] = (string) $av;
                } elseif ( $ak == 'method' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['method'] = $av;
                } elseif ( $ak == 'callerId' ) {
                    $dial['callerId'] = (string) $av;
                } elseif ( $ak == 'ringTone' ) {
                    $dial['ringTone'] = ( (string) $av == 'true' ) ? true : false;
                } elseif ( $ak == 'timeLimit' ) {
                    $dial['timeLimit'] = (int) $av;
                } elseif ( $ak == 'hangupOnStar' ) {
                    $dial['hangupOnStar'] = ( (string) $av == 'true' ) ? true : false;
                } elseif ( $ak == 'recordingStatusCallback' ) {
                    $dial['recordingStatusCallback'] = (string) $av;
                }elseif ( $ak == 'statusCallback' ) {
                    $dial['statusCallback'] = (string) $av;
                } elseif ( $ak == 'record' ) {
                    $record = 1;
                    switch ( strtolower( (string) $av ) ) {
                    case "record-from-answer":
                        $record = 1;
                        break;
                    case "record-from-ringing":
                        $record = 2;
                        break;

                    }

                    $dial['record'] = $record;
                }

            }

            if($dial['answerOnBridge'] == true ) $dial['ringTone'] = false;

            foreach ( $elem as $name => $child ) {
                if ( $name == 'play' || $name == 'say' ) {
                    $play = array_merge( $play, $this->parseElement( $child ) );
                } elseif ( $name == 'queue' ) {
                    $dial['to']    = (string) $child; //$dial['queue'] = true;
                    $dial['queue'] = ['url' => $this->url];

                    foreach ( $child->attributes() as $ak => $av ) {

                        if ( $ak == 'url' ) {
                            $dial['queue']['url'] = (string) $av;
                        } else

                        if ( $ak == 'method' ) {
                            $dial['queue']['method'] = (string) $av;
                        }

                    }

                }

            }
            //Log::info("parsing all dial here");
            //Log::info($dial);
            if ( isset( $dial['channel'] ) || isset( $dial['channel_id'] ) || isset( $dial['queue'] ) ) {
                $actions[] = $dial;

            } else {
                $actions = $this->parseElements( FunctionCall::getOutboundRoutes( (string) $elem, $this->org_id ) );
            }

            unset( $dial['to'] );

            foreach ( $actions as $k => $action ) {

                if ( $action['verb'] == 'dial' ) {
                   
                    //Log::info($action);    
                    
                    if ( isset( $action['queue'] ) ) {
                        $action['actions'] = [];
                    } else

                    $action = array_merge( $action, $dial );

                    if ( isset( $action['ringTone'] ) && $action['ringTone'] != false ) {
                        $action['actions'] = $this->parseElements( VoiceResponse::genXmlElelment( 'play', storage_path( 'app/public/sounds/ring_tone.wav' ), ['localfile' => "true"] ) );
                    }

                    $actions[$k] = $action;
                }

            }

        } elseif ( $tag == 'redirect' ) {

            $verb = ['verb' => $tag, 'url' => (string) $elem, 'method' => 'POST'];

            foreach ( $elem->attributes() as $ak => $av ) {

                if ( $ak == 'method' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['method'] = $av;
                }

            }
            if ( empty( $verb['url'] ) ) {
                $verb['url'] = $this->url;
            }

            $actions[] = $verb;
        } elseif ( $tag == 'bridge' ) {
            $verb = ['verb' => $tag, 'bridge_call_id' => (string) $elem, 'bridgeAfterEstablish' => false];

            foreach ( $elem->attributes() as $ak => $av ) {

                if ( $ak == 'bridgeAfterEstablish' ) {
                    $verb['bridgeAfterEstablish'] = ( (string) $av == 'true' ) ? true : false;
                }

            }

            $actions[] = $verb;
        } elseif ( $tag == 'record' ) {
            $verb = ['verb' => $tag, 'action' => $this->url, 'method' => 'POST', 'timeout' => 5, 'finishOnKey' => 0, 'transcribe' => false, 'trim' => true, 'beep' => true];

            foreach ( $elem->attributes() as $ak => $av ) {

                if ( $ak == 'action' ) {
                    $verb['action'] = (string) $av;
                } else

                if ( $ak == 'action' ) {
                    $verb['action'] = (string) $av;
                } else

                if ( $ak == 'method' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['action'] = (string) $av;
                } else

                if ( $ak == 'timeout' ) {
                    $verb['timeout'] = (int) $av;
                } else

                if ( $ak == 'finishOnKey' ) {
                    $verb['finishOnKey'] = ord( (string) $av );
                } else

                if ( $ak == 'transcribe' ) {
                    $verb['transcribe'] = ( (string) $av == 'true' ) ? true : false;
                } else

                if ( $ak == 'trim' ) {
                    $verb['trim'] = ( (string) $av == 'true' ) ? true : false;
                } else

                if ( $ak == 'beep' ) {
                    $verb['beep'] = ( (string) $av == 'true' ) ? true : false;
                }

            }

            if ( $verb['beep'] == true ) {
                $actions = $this->parseElements( VoiceResponse::genXmlElelment( 'play', storage_path( 'app/public/sounds/beep.wav' ), ['localfile' => "true"] ) );
            }

            $actions[] = $verb;
        } elseif ( $tag == 'enqueue' ) {
            $verb = ['verb' => 'dial', 'answerOnBridge' => false, 'to' => (string) $elem, 'enqueue' => ['url' => $this->url]];

            foreach ( $elem->attributes() as $ak => $av ) {

                if ( $ak == 'waitUrlMethod' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['enqueue']['method'] = $av;
                } else

                if ( $ak == 'waitUrl' ) {
                    $verb['enqueue']['url'] = (string) $av;
                } else

                if ( $ak == 'answerOnBridge' ) {
                    $verb['answerOnBridge'] = ( (string) $av == 'true' ) ? true : false;
                } else

                if ( $ak == 'method' && in_array( $av, ['POST', 'GET'] ) ) {
                    $verb['method'] = $av;
                } else

                if ( $ak == 'action' ) {
                    $verb['action'] = (string) $av;
                }

            }

            $actions[] = $verb;
        } elseif ( $tag == 'say' ) {
            $options = [];

//$text    = (string) $elem;

            foreach ( $elem->attributes() as $ak => $av ) {
                $options[$ak] = (string) $av;
            }

            $actions = $this->parseElement( $this->sayVerb( (string) $elem, $options ) );
        } elseif ( $tag == 'hangup' ) {
            $verb      = ['verb' => $tag];
            $actions[] = $verb;
        } elseif ( $tag == 'leave' ) {
            $verb      = ['verb' => $tag];
            $actions[] = $verb;
        } elseif ( $tag == 'pause' ) {
            $length = (int) $elem;

            foreach ( $elem->attributes() as $ak => $av ) {

                if ( $ak == 'length' ) {
                    $length = (int) $av;
                }

            }

            $length = abs( $length );

            if ( $length <= 0 ) {
                $length = 1;
            }

            $actions = $this->parseElement( VoiceResponse::genXmlElelment( 'play', storage_path( 'app/public/sounds/silence_1.wav' ), ['localfile' => "true", 'loop' => $length] ) );
        } elseif ( $tag == 'sms' ) {
            $verb = ['verb' => 'sms', 'body' => (string) $elem];

            foreach ( $elem->attributes() as $ak => $av ) {
                $verb[$ak] = (string) $av;
            }

            $actions[] = $verb;
        }

        return $actions;
    }

    /**
     * @return boolean
     */
    function url_exists( $url ) {
        $client = new GuzzleHttp\Client();

        try {
            $client->head( $url );

            return true;
        } catch ( GuzzleHttp\Exception\ClientException $e ) {
            return false;
        }

    }

}
