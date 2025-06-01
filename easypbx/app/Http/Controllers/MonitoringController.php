<?php

namespace App\Http\Controllers;

use App\Enums\CallStatusEnum;
use App\Enums\QueueStatusEnum;
use App\Models\Call;
use App\Models\CallHistory;
use App\Models\Queue;
use App\Models\SipChannel;
use App\Models\SmsHistory;
use App\Models\Trunk;
use App\Models\CallQueue;
use App\Models\CallParkingLog;
use Carbon\Carbon;
use App\Tts\Tts;
use Illuminate\Http\Request;

class MonitoringController extends Controller {
    public function __construct(){
        config(['menu.group' => 'menu-monitoring']);
    }
    public function trunkLog( Request $request ) {
        $trunks = Trunk::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'sip_user_id', )->toArray();
        $calls = Call::where( 'organization_id', auth()->user()->organization_id )->whereIn( 'sip_user_id', array_keys( $trunks ) );

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {

                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'from' ) {
                    $calls->where( 'caller_id', $searchColumnArr[1] );
                } elseif ( $searchColumnArr[0] == 'to' ) {
                    $calls->where( 'destination', $searchColumnArr[1] );
                } elseif ( $searchColumnArr[0] == 'sip_user_id' ) {
                    $calls->where( 'sip_user_id', $searchColumnArr[1] );
                } elseif ( $searchColumnArr[0] == 'date' ) {
                    $calls->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );
                }
            }
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $calls->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $calls->orderBy( $sorta[0], $sorta[1] );
        } else {
            $calls->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'trunk_logs.csv';
            $calls    = $calls->get();
            $headers  = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['from', 'to', 'trunk', 'channel', 'date', 'duration', 'type', 'status'];

            $callback = function () use ( $calls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $call ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'from' ) {
                            $row[$column] = $call->caller_id;
                        } else
                        if ( $column == 'to' ) {
                            $row[$column] = $call->destination;
                        } else
                        if ( $column == 'trunk' ) {
                            $trunk        = isset( $call->sipUser->trunk ) ? $call->sipUser->trunk : null;
                            $row[$column] = optional( $trunk )->name;
                        } else
                        if ( $column == 'date' ) {
                            $row[$column] = $call->created_at;
                        } else
                        if ( $column == 'type' ) {
                            $row[$column] = $call->uas == 1 ? 'Incoming' : 'Outgoing';
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $call->status->getText();
                        } else {
                            $row[$column] = $call->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $calls = $calls->paginate( $perPage );

        foreach($calls as $key => $call){
            if(CallHistory::where('call_id', $call->id)->orWhere('bridge_call_id', $call->id)->exists()){
                $call->bridge = 1;
            }
            else{
                $call->bridge = 0;
            }
        }

        

        $calls->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage, 'q' => $q] );

        $view = $request->ajax() ? 'monitoring.trunks.table' : 'monitoring.trunks.index';

        return view( $view, compact( 'calls', 'trunks' ) );
    }

    public function activeCall( Request $request ) {
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $calls = Call::where( 'organization_id', auth()->user()->organization_id )->whereIn( 'status', [0, 1, 2] );

        if (  ! empty( $q ) ) {
            $calls->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $calls->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $calls->orderBy( $sorta[0], $sorta[1] );
        } else {
            $calls->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'active_calls.csv';
            $calls    = $calls->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['date', 'destination', 'channel', 'duration', 'uas', 'status'];

            $callback = function () use ( $calls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $call ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'date' ) {
                            $row[$column] = $call->connect_time;
                        } elseif ( $column == 'uas' ) {
                            $row[$column] = $call->uas == 1 ? 'Incoming' : 'Outgoing';
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $call->status->getText();
                        } else {
                            $row[$column] = $call->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $calls = $calls->paginate( $perPage );

        $calls->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage, 'q' => $q] );

        $statuses = CallStatusEnum::activeCallStatuses();

        $view = $request->ajax() ? 'monitoring.active_calls.table' : 'monitoring.active_calls.index';

        return view( $view, compact( 'calls', 'statuses' ) );
    }

    public function callHistories( Request $request ) {
        
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $calls = CallHistory::with( ['call', 'bridgeCall'] )->where( 'organization_id', auth()->user()->organization_id )->where('bridge_call_id','!=','');

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'from' ) {
                    $calls->whereHas( 'bridgeCall', function ( $query ) use ( $searchColumnArr ) {
                        $query->where( 'caller_id', $searchColumnArr[1] );
                    } );
                } elseif ( $searchColumnArr[0] == 'to' ) {
                    $calls->whereHas( 'bridgeCall', function ( $query ) use ( $searchColumnArr ) {
                        $query->where( 'destination', $searchColumnArr[1] );
                    } );
                } elseif ( $searchColumnArr[0] == 'received_by' ) {
                    $calls->whereHas( 'bridgeCall', function ( $query ) use ( $searchColumnArr ) {
                        $query->where( 'destination', $searchColumnArr[1] );
                    } );
                }
                elseif ( $searchColumnArr[0] == 'date' ) {
                    
                    $calls->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );

                }

            }

        }
        
        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $calls->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $calls->orderBy( $sorta[0], $sorta[1] );
        } else {
            $calls->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'call_histories.csv';
           
            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];
        
            $columns  = ['date', 'from', 'to', 'received by', 'channel', 'duration', 'status'];
            $callback = function () use ( $calls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );
        
        
        
               $calls->chunk(100, function ($callsdata) use($columns, $file) {
                    foreach ( $callsdata as $call ) {
        
                        foreach ( $columns as $column ) {
        
                            if ( $column == 'date' ) {
                                $row[$column] = $call->created_at;
                            } elseif ( $column == 'from' ) {
                                $row[$column] = $call->call->caller_id;
                            } elseif ( $column == 'to' ) {
                                $row[$column] = optional( $call )->destination;
                            } elseif ( $column == 'received by' ) {
                                $row[$column] = optional( $call->bridgeCall )->destination;
                            } elseif ( $column == 'channel' ) {
                                $row[$column] = optional( $call->bridgeCall )->channel;
        
                            } elseif ( $column == 'status' ) {
                                $row[$column] = $call->status->getText();
                            } else {
                                $row[$column] = $call->{$column};
                            }
        
                        }
        
                        fputcsv( $file, $row );
                    }
                 });
        
                fclose( $file );
            };
        
            return response()->stream( $callback, 200, $headers );
        }

        $calls = $calls->paginate( $perPage );

        $calls->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage, 'q' => $q] );

        $view = $request->ajax() ? 'monitoring.call_histories.table' : 'monitoring.call_histories.index';
        
        $statuses = CallStatusEnum::callLogStatuses();

        return view( $view, compact( 'calls', 'statuses' ) );
    }

    public function summarizeConversation(Request $request) {
        $record_file = $request->get('record_file');
        $stt_only = $request->get('stt_only', false);

        if (empty($record_file)) {
            return response()->json(['status' => false, 'message' => 'Record file is required.'], 400);
        }

        $record_file_path = storage_path('app/public/' . $record_file);

        if (!file_exists($record_file_path)) {
            return response()->json(['status' => false, 'message' => 'Record file not found.'], 404);
        }

        $res = Tts::speechToText($record_file_path, auth()->user()->organization_id);

        if($res && $stt_only){
            return response()->json(['status' => true, 'text' => $res['text']]);
        }

        if($res || $res['text']){
            $text = $res['text'];


            $prompt = "You are a helpful assistant. Please summarize the following text: $text";
            $instruction = "always provide the output as a dialoge format customer and agent. each conversation wrap a html div include a class name 'conversation' and each conversation wrap a html div include a class name 'customer' or 'agent'";
            $llmRes = Tts::llm($prompt, $instruction, auth()->user()->organization_id);

            return response()->json(['status' => true, 'text' => $llmRes]);
        }

        return response()->json(['status' => false, 'message' => 'Failed to process the record file.'], 500);
    }

    public function callLog( Request $request ) {
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        
        $calls = Call::with( 'records' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'caller_id' ) {
                    $calls->where( function ( $query ) use ( $searchColumnArr ) {
                        $query->where( 'caller_id', $searchColumnArr[1] )->orWhere( 'channel', 'LIKE', '%' . $searchColumnArr[1] . '%' );
                    } );

                } elseif ( $searchColumnArr[0] == 'date' ) {
                    $calls->whereDate( 'connect_time', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );

                } else {
                    $calls->where( $searchColumnArr[0], $searchColumnArr[1] );
                }

            }
        }

        // return $calls->get();

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            

            if( $filtera[0] == 'status' ) {

                if(is_numeric($filtera[1])){
                    $calls->where( $filtera[0], '=', $filtera[1] );    
                }

            }
            else{
                $calls->where( $filtera[0], '=', $filtera[1] );
            }
            
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $calls->orderBy( $sorta[0], $sorta[1] );
        } else {
            $calls->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'call_logs.csv';
            $calls    = $calls->get();
            $headers  = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['destination', 'caller_id', 'channel', 'connect_time', 'duration', 'type', 'status'];

            $callback = function () use ( $calls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $call ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'type' ) {
                            $row[$column] = $call->uas == 1 ? 'Incoming' : 'Outgoing';
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $call->status->getText();
                        } else {
                            $row[$column] = $call->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $calls = $calls->paginate( $perPage );

        $calls->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage, 'q' => $q] );

        $view = $request->ajax() ? 'monitoring.call_logs.table' : 'monitoring.call_logs.index';

        $statuses = CallStatusEnum::CallStatuses();

        return view( $view, compact( 'calls', 'statuses' ) );
    }


    public function queueCall( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $calls = Queue::with( ['call', 'bridgeCall'] )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'name' ) {
                    $calls->where( 'queue_name', 'LIKE', '%' . $searchColumnArr[1] . '%' );
                } elseif ( $searchColumnArr[0] == 'date' ) {
                    $calls->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );
                } elseif ( $searchColumnArr[0] == 'destination' ) {
                    $calls->whereHas( 'bridgeCall', function ( $query ) use ( $searchColumnArr ) {
                        $query->where( $searchColumnArr[0], $searchColumnArr[1] );
                    } );
                } else {
                    $calls->whereHas( 'call', function ( $query ) use ( $searchColumnArr ) {
                        $query->where( $searchColumnArr[0], $searchColumnArr[1] );
                    } );
                }

            }

        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $calls->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $calls->orderBy( $sorta[0], $sorta[1] );
        } else {
            $calls->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'queue_calls.csv';
            $calls    = $calls->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns  = ['date', 'queue_name', 'caller_id', 'agent', 'number', 'duration', 'waiting_duration', 'status'];
            $callback = function () use ( $calls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $call ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'date' ) {
                            $row[$column] = $call->created_at;
                        } elseif ( $column == 'caller_id' ) {
                            $row[$column] = $call->call->caller_id;
                        } elseif ( $column == 'agent' ) {
                            $row[$column] = optional( $call->bridgeCall )->destination;
                        } elseif ( $column == 'number' ) {
                            $row[$column] = $call->call->destination;
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $call->status->getText();
                        } else {
                            $row[$column] = $call->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        
        $calls = $calls->paginate( $perPage );

        $calls->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage, 'q' => $q] );

        $view = $request->ajax() ? 'monitoring.queues.table' : 'monitoring.queues.index';

        $statuses = QueueStatusEnum::statuses();


        $queueList = CallQueue::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');

        
         
        return view( $view, compact( 'calls', 'statuses', 'queueList' ) );
    }



    public function preview( $id ) {
        $queue = Queue::where( 'call_id', $id )->first();

        if ( $queue ) {
            $path = asset( 'storage/' . $queue->record_file );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );
    }

    public function history_preview( $id ) {
        $call = CallHistory::where( 'call_id', $id )->first();

        if ( $call ) {
            $path = asset( 'storage/' . $call->record_file );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );
    }

    public function activeParkingCalls(Request $request){
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        

        $cpLogs = CallParkingLog::with(['call','callParking'])->where( 'organization_id', auth()->user()->organization_id );
       // dd($cpLogs->toSQL());
        if (  ! empty( $q ) ) {
            $cpLogs->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $cpLogs->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $cpLogs->orderBy( $sorta[0], $sorta[1] );
        } else {
            $cpLogs->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'active_parking_calls.csv';

            $logs = $cpLogs->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = [];

            $callback = function () use ( $logs, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $logs as $call ) {

                    foreach ( $columns as $column ) {

                        $row[$column] = $call->{$column};

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }
        
        $cpLogs = $cpLogs->paginate( $perPage );
       
        $cpLogs->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage] );

        $view = $request->ajax() ? 'monitoring.active_parking_calls.table' : 'monitoring.active_parking_calls.index';
        
        return view( $view, compact( 'cpLogs' ) );

    }

    public function activeChannels( Request $request ) {
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        SipChannel::whereRaw( "'" . now() . "' > TIMESTAMPADD(SECOND,expire,updated_at)" )->delete();

        $channels = SipChannel::with( 'sipUser' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $channels->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $channels->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $channels->orderBy( $sorta[0], $sorta[1] );
        } else {
            $channels->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'active_channels.csv';

            $channels = $channels->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['sip_user', 'type', 'location', 'expire', 'user_agent', 'date'];

            $callback = function () use ( $channels, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $channels as $call ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'sip_user' ) {
                            $row[$column] = $call->sipUser->username;
                        } elseif ( $column == 'type' ) {
                            $row[$column] = ( $call->sipUser->peer ) ? "Peer" : "User";

                        } elseif ( $column == 'user_agent' ) {
                            $row[$column] = $call->ua;
                        } elseif ( $column == 'date' ) {
                            $row[$column] = $call->created_at;
                        } else {
                            $row[$column] = $call->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $channels = $channels->paginate( $perPage );

        $channels->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage] );

        $view = $request->ajax() ? 'monitoring.active_channels.table' : 'monitoring.active_channels.index';

        return view( $view, compact( 'channels' ) );
    }

    public function smsHistory( Request $request ) {
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $histories = SmsHistory::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'to' ) {
                    $histories->where( 'to', 'LIKE', '%' . $searchColumnArr[1] . '%' );

                } elseif ( $searchColumnArr[0] == 'date' ) {
                    $histories->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );
                }

            }

        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $histories->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $histories->orderBy( $sorta[0], $sorta[1] );
        } else {
            $histories->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'sms_histories.csv';

            $histories = $histories->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['date', 'from', 'to', 'body', 'status'];

            $callback = function () use ( $histories, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $histories as $sms ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'date' ) {
                            $row[$column] = $sms->created_at;
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $sms->status->getText();
                        } else {
                            $row[$column] = $sms->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $histories = $histories->paginate( $perPage );

        $histories->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage] );

        $view = $request->ajax() ? 'monitoring.sms_histories.table' : 'monitoring.sms_histories.index';

        return view( $view, compact( 'histories' ) );
    }

}
