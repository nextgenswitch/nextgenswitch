<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FunctionCall;
use Carbon\Carbon;
use App\Tts\OpenAi;
use App\Models\SipChannel;
use App\Models\CallHistory;
use App\Models\Extension;
use App\Models\User;
use App\Models\Trunk;
use App\Models\Call;
use App\Models\Queue;
use App\Enums\CallStatusEnum;
use App\Enums\QueueStatusEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\VoiceResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Response;
use DB;


class DashboardController extends Controller
{



    public function callStats(Request $request){
        $organizationId = auth()->user()->organization_id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $calls = Call::where('organization_id', $organizationId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(CASE WHEN status = 3 THEN 1 END) as successed'),
                DB::raw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed')
            )
            ->first();

        return response()->json($calls);
    }

    public function trunkStats(Request $request){
        $organizationId = auth()->user()->organization_id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $truckSipUserIds = Trunk::where( 'organization_id', $organizationId )->pluck('sip_user_id')->toArray();
        $calls = Call::where('organization_id', $organizationId)->whereIn('sip_user_id', $truckSipUserIds)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(CASE WHEN status = 3 THEN 1 END) as successed'),
                DB::raw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed')
            )
            ->first();

        return response()->json($calls);
    }

    public function extensionStats(){
        $extDstIds = Extension::where( 'organization_id', auth()->user()->organization_id )->where('extension_type', '1')->where('status', '1')->pluck('destination_id')->toArray();
        
        SipChannel::whereRaw( "'" . now() . "' > TIMESTAMPADD(SECOND,expire,updated_at)" )->delete();

        $onlineExts = SipChannel::with( 'sipUser' )->where( 'organization_id', auth()->user()->organization_id )->whereIn('sip_user_id', $extDstIds)->count();
        
        return response()->json(['online' =>  $onlineExts, 'offline' => count($extDstIds) - $onlineExts]);
    }

    public function queueStats(Request $request){
        $organizationId = auth()->user()->organization_id;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $queueQuery = Queue::where( 'organization_id', $organizationId )->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        $ans_calls = $queueQuery->clone()->where('duration', '>', 0)->where('status', QueueStatusEnum::Disconnected)->count();
        $abandoned_calls = $queueQuery->clone()->where('status', QueueStatusEnum::Abandoned)->count();
        $timeout_calls = $queueQuery->clone()->where('status', QueueStatusEnum::Timeout)->count();

        return response()->json([
            'answer' => $ans_calls,
            'abandoned' => $abandoned_calls,
            'timeout' => $timeout_calls
        ]);
    }


    public function indexNew(){
        $orid = auth()->user()->organization_id;
        
        $trunkSipIds = Trunk::where('organization_id', $orid)->pluck('sip_user_id')->toArray();
        $Calls = Call::where( 'organization_id', $orid )->where('uas', 0)->whereDate('created_at', Carbon::today())->whereIn('sip_user_id', $trunkSipIds);
        
        $total_calls = $Calls->clone()->count();
        $total_duration = $Calls->clone()->sum('duration');
        $total_success_calls = $Calls->clone()->where('status', CallStatusEnum::Disconnected)->where('duration', '>', 0)->count();


        
        $avg_success_rate = 0;
        $avg_call_duration = 0;

        if($total_calls > 0)
            $avg_success_rate = (floatval($total_success_calls) * floatval(100) ) / floatval($total_calls);

        if($total_success_calls > 0)
            $avg_call_duration = floatval($total_duration) / floatval($total_success_calls);

            
        return view('dashboard.ui', compact('total_calls', 'total_duration', 'avg_success_rate', 'avg_call_duration'));
    }

    public function bridgeCallStats(){
        $last7DaysCalls = CallHistory::where('organization_id', auth()->user()->organization_id)
            ->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as successed')
            ->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        
        $data = array();

        if($last7DaysCalls){
            foreach($last7DaysCalls as $row){
                $data['date'][] = $row->date;
                $data['successed'][] = $row->successed;
                $data['failed'][] = $row->failed;
            }
        }
        
        $data = count($data) ? $data : [];
        
        return response()->json($data);
    }

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

    public function dbadmin(Request $request){
        $db_connection = config('database');
        $db = $db_connection['connections'][$db_connection['default']];
     if ($request->isMethod('post')){       
          /*   $_POST['auth']['driver'] = ($db['driver'] == 'mysql')?"server":$db['driver'];
            $_POST['auth']['server'] = $db['host'] . ":" . $db['port'];
            $_POST['auth']['db'] = $db['database'];
            $_POST['auth']['username'] = $db['username'];
            $_POST['auth']['password'] = $db['password'];  */
    }else{
           if(!isset($_GET['server'])){
                $_POST['auth']['driver'] = ($db['driver'] == 'mysql')?"server":$db['driver'];
                $_POST['auth']['server'] = $db['host'] . ":" . $db['port'];
                $_POST['auth']['db'] = $db['database'];
                $_POST['auth']['username'] = $db['username'];
                $_POST['auth']['password'] = $db['password'];  
           }
           
    }   

     
        include_once __DIR__ . '/../../../resources/adminer-5.0.4-en.php';
        return response()->noContent();
    }


}
