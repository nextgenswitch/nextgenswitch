<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FunctionCall;
use Carbon\Carbon;
use App\Tts\OpenAi;
use App\Models\SipChannel;
use App\Models\CallHistory;
use App\Models\Extension;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\VoiceResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use DB;


class DashboardController extends Controller
{

    public function index(){
        $calls = CallHistory::where( 'organization_id', auth()->user()->organization_id )
                ->whereDate('created_at', Carbon::today())
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as successed')
                ->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed')->first();
                
        
        $extDstIds = Extension::where( 'organization_id', auth()->user()->organization_id )->where('extension_type', '1')->where('status', '1')->pluck('destination_id')->toArray();
        
        SipChannel::whereRaw( "'" . now() . "' > TIMESTAMPADD(SECOND,expire,updated_at)" )->delete();

        $onlineExts = SipChannel::with( 'sipUser' )->where( 'organization_id', auth()->user()->organization_id )->whereIn('sip_user_id', $extDstIds)->count();
        
        $extensions = [
            'total' => count($extDstIds),
            'online' => $onlineExts
        ];


        $hourlyCalls = CallHistory::where( 'organization_id', auth()->user()->organization_id )
        ->whereDate('created_at', Carbon::today())
        ->select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('count(*) as total'),
            DB::raw('COUNT(CASE WHEN status = 3 THEN 1 END) as successed'),
            DB::raw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed')

        )
        ->groupBy(DB::raw('HOUR(created_at)'))
        ->get();

        
        $hourlyReports = array();

        for ($i=0; $i <= (int) Carbon::now()->format('H'); $i++) { 
            $hourlyReports['total'][] = 0;
            $hourlyReports['successed'][] = 0;
            $hourlyReports['failed'][] = 0;
            $hourlyReports['time'][] = (strlen($i) == 1 ? '0' : '') . $i . ':' . '00' ;
        }

        foreach ($hourlyCalls as $key => $hourlyCall) {
            $hourlyReports['total'][$hourlyCall['hour']] = $hourlyCall['total'];
            $hourlyReports['successed'][$hourlyCall['hour']] = $hourlyCall['successed'];
            $hourlyReports['failed'][$hourlyCall['hour']] = $hourlyCall['failed'];
        }

        

        return view('dashboard.index')->with([
            'calls' => json_encode($calls),
            'extensions' => json_encode($extensions),
            'hourlyReports' => json_encode($hourlyReports)
        ]);

    }

    public function dialer(){
        return view('dashboard.dialer');
    }

    public function dialer_connect(Request $request){

       
       return FunctionCall::send_call(['to'=>$request->query('to'),'from'=>'easypbx','response'=>route('dashboard.dialer.response')]);

    }

    public function dialer_dial(Request $request){
        $voice_response = new VoiceResponse;
        $voice_response->dial($request->query('to'),['action'=>route('dashboard.dialer.response')]);
        $voice_response->redirect(route('dashboard.dialer.response'));
        return FunctionCall::modify_call($request->query('call_id'),['responseXml'=>$voice_response->xml()]);
 
    }

    public function dialer_response(){
         $voice_response = new VoiceResponse;
         $voice_response->pause(10);
         $voice_response->redirect(route('dashboard.dialer.response'));
         return $voice_response->xml();
    }


}
