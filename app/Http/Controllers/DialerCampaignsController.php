<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactGroup;
use App\Models\DialerCampaign;
use App\Models\Extension;
use App\Models\Contact;
use App\Models\Script;
use App\Models\SmsProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\CustomForm;
use App\Models\CallHistory;
use Exception;
use Illuminate\Http\Request;
use Schema;
use App\Models\DialerCampaignCall;
use App\Http\Controllers\Api\FunctionCall;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\Functions\CallHandler;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class DialerCampaignsController extends Controller {

    protected $totalContacts = 0;
    protected $totalProccesContacts = 0;
    protected $totalDuration = 0;
    protected $totalTalks = 0;

    /**
     * Display a listing of the dialer campaigns.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 
    public function index( Request $request ) {

        $q              = $request->get( 'q' ) ?: '';
        $perPage        = $request->get( 'per_page' ) ?: 10;
        $filter         = $request->get( 'filter' ) ?: '';
        $sort           = $request->get( 'sort' ) ?: '';
        $dialerCampaign = DialerCampaign::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $dialerCampaign->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $dialerCampaign->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $dialerCampaign->orderBy( $sorta[0], $sorta[1] );
        } else {
            $dialerCampaign->orderBy( 'created_at', 'DESC' );
        }

        $dialerCampaigns = $dialerCampaign->paginate( $perPage );

        $dialerCampaigns->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'dialerCampaigns.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing( ( new DialerCampaign )->getTable() );

            $callback = function () use ( $dialerCampaigns, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $dialerCampaigns as $dialerCampaign ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $dialerCampaign->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'dialer_campaigns.table', compact( 'dialerCampaigns' ) );
        }

        return view( 'dialer_campaigns.index', compact( 'dialerCampaigns' ) );

    }

    

    /**
     * Show the form for creating a new dialer campaign.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        $agents = Extension::where( 'organization_id', '=', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'name', 'id' )->all();

        $scripts = Script::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $forms = CustomForm::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $dialerCampaign = new DialerCampaign( ['max_retry' => 1, 'call_limit' => 3, 'start_at' => '08:00:00', 'end_at' => '18:00:00', 'schedule_days' => array_keys( config( 'enums.weekdays' ) )] );

        if ( $request->ajax() ) {
            return view( 'dialer_campaigns.form', compact( 'contact_groups', 'agents', 'scripts', 'forms' ) )->with( ['action' => route( 'dialer_campaigns.dialer_campaign.store' ), 'dialerCampaign' => null, 'method' => 'POST'] );
        } else {
            return view( 'dialer_campaigns.create', compact('dialerCampaign', 'contact_groups', 'agents', 'scripts', 'forms' ) );
        }

    }

    /**
     * Store a new dialer campaign in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;
        

        DialerCampaign::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'dialer_campaigns.dialer_campaign.index' )
            ->with( 'success_message', __( 'Dialer Campaign was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified dialer campaign.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        $dialerCampaign = DialerCampaign::findOrFail( $id );
        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        $agents = Extension::where( 'organization_id', '=', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'name', 'id' )->all();
        $scripts = Script::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $forms = CustomForm::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');

        if ( $request->ajax() ) {
            return view( 'dialer_campaigns.form', compact( 'dialerCampaign', 'contact_groups', 'agents', 'scripts', 'forms' ) )->with( ['action' => route( 'dialer_campaigns.dialer_campaign.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'dialer_campaigns.edit', compact( 'dialerCampaign', 'contact_groups', 'agents', 'scripts', 'forms' ) );
        }

    }

    public function clone( $id, Request $request ) {
        $dialerCampaign = DialerCampaign::findOrFail( $id );
        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        $agents = Extension::where( 'organization_id', '=', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'name', 'id' )->all();
        $scripts = Script::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $forms = CustomForm::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');

        if ( $request->ajax() ) {
            return view( 'dialer_campaigns.form', compact( 'dialerCampaign', 'contact_groups', 'agents', 'scripts', 'forms' ) )->with( ['action' => route( 'dialer_campaigns.dialer_campaign.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'dialer_campaigns.create', compact( 'dialerCampaign', 'contact_groups', 'agents', 'scripts', 'forms' ) );
        }

    }

    /**
     * Update the specified dialer campaign in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        $dialerCampaign = DialerCampaign::findOrFail( $id );
        $dialerCampaign->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'dialer_campaigns.dialer_campaign.index' )
            ->with( 'success_message', __( 'Dialer Campaign was successfully updated.' ) );

    }

    /**
     * Remove the specified dialer campaign from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            $dialerCampaign = DialerCampaign::findOrFail( $id );
            $dialerCampaign->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'dialer_campaigns.dialer_campaign.index' )
                    ->with( 'success_message', __( 'Dialer Campaign was successfully deleted.' ) );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()->withInput()
                    ->withErrors( ['unexpected_error' => __( 'Unexpected error occurred while trying to process your request.' )] );
            }

        }

    }

    /**
     * update the specified dialer campaign for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            $dialerCampaign = DialerCampaign::findOrFail( $id );

            $dialerCampaign->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified dialer campaign for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                DialerCampaign::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn( ( new DialerCampaign )->getTable(), $field ) ) {
                        DialerCampaign::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request ) {
        $rules = [
            'call_interval'  => 'required|numeric',
            'contact_groups' => 'required|array|min:1|max:100',
            'description'    => 'nullable|string|min:0|max:191',
            'start_at'       => 'required|date_format:H:i',
            'end_at'         => 'required|date_format:H:i',
            'end_date'       => 'nullable|date_format:Y-m-d',
            'name'           => 'required|string|min:1|max:191',
            'schedule_days'  => 'required|array|min:1|max:100',
            'timezone'       => 'required|string|min:1|max:100',
            'script_id'         => 'required|numeric',
            'form_id'         => 'required|numeric'
        ];

        $data = $request->validate( $rules );

        return $data;
    }

    public function getContact($id){
        $campaign = DialerCampaign::findOrFail( $id );
        $contact = $this->getCampaignContact($campaign);

        if($contact){
            $content = optional($campaign->script)->content;

            if($content){
                $contact->script_content = Script::prepare($content, $contact);
            }   
            $campaign->update(['status' => '1']);
        }else{
            $campaign->update(['status' => '3']);
            return response()->json(['cam_status' => $campaign->status]);
        }
        return $contact;

    }

   

    public function getStats($campaign){
        $stats = array();
        $stats['total_contacts'] = Contact::getContacts($campaign->contact_groups)->count();
        $stats['processed_contacts'] = DialerCampaignCall::where('dialer_campaign_id' , $campaign->id)->count();
        $stats['total_duration'] = DialerCampaignCall::where('dialer_campaign_id' , $campaign->id)->sum('duration');
        $stats['total_successfull'] = DialerCampaignCall::where('dialer_campaign_id' , $campaign->id)->where('status',CallStatusEnum::Disconnected)->count();
        $stats['in_time'] = DialerCampaign::inTime($campaign);
        return $stats;    
    }   

    public function humanReadable($sec){
        if ( $sec > 0 ) {
            return CarbonInterval::seconds( $sec )->cascade()->forHumans();
        }

        return '0 seconds';
    }

    public function updateCampaignCall($id){
        $campaign = DialerCampaign::findOrFail( $id );
        $calldata = request()->input();
        info("updating campaign call");
        info($calldata);
        $data = [
            'dialer_campaign_id' => $id,
            'tel' => $calldata['to'],
            'duration' => isset($calldata['duration'])?$calldata['duration']:0,
            'status' => $calldata['status-code'],
            'record_file' => isset($calldata['record_file']) ? $calldata['record_file'] : ''
        ];
        unset($calldata['status']);
        $data = array_merge($data,$calldata);
        DialerCampaignCall::updateOrCreate(['tel' => $calldata['to'], 'dialer_campaign_id' => $id], $data);

        if($data['status'] >= CallStatusEnum::Disconnected->value )
            $campaign->update(['status' => '2']);
        return $this->getStats($campaign);
    }

    public function run($id){
       
        $campaign = DialerCampaign::findOrFail( $id );
        
 
        return view('dialer_campaigns.run', compact('campaign'))->with([
            'stats' => $this->getStats($campaign)
        ]);


    }

    



    public function getCampaignContact( $dCampaign ): array | object | bool {
        $contacts = Contact::where( 'organization_id', auth()->user()->organization_id );

        foreach ( $dCampaign->contact_groups as $key => $groupId ) {
            $statement = $key === 0 ? 'whereRaw' : 'orWhereRaw';
            $contacts->{$statement}( 'FIND_IN_SET(?, contact_groups)', [$groupId] );
        }

        $contacts = Contact::getContacts($dCampaign->contact_groups )->toArray();
        $dialerCampaignCallContacts = DialerCampaignCall::where('dialer_campaign_id', $dCampaign->id)->pluck('tel')->toArray();
   
        $unUsedContacts = array_diff( $contacts, $dialerCampaignCallContacts );
        
        if ( count($unUsedContacts) ){
            return Contact::find(array_key_first($unUsedContacts));
        }

        return false;
        
    }

    public function formData(Request $request){
        $data = $request->except(['_token', 'caller_id', 'dcam_id']);

        $dialerCampaignCall = DialerCampaignCall::where('tel', $request->input('caller_id'))
        ->where('dialer_campaign_id', $request->input('dcam_id'))->first();
        
        if($dialerCampaignCall){
            $dcall = $dialerCampaignCall->update(['form_data' => json_encode($data)]);

            if( $dcall ) return response()->json(['status' => 'success']);   
        }
        

        return response()->json(['status' => 'failed']);
    }


    public function updateContact(Request $request){
        $data = $request->except(["_token", 'caller_id']);
        
        $contact = Contact::where('organization_id', auth()->user()->organization_id)->where('id', $request->input('id'))->update($data);
        
        $status = $contact ? 'success' : 'failed';

        return response()->json(['status' => $status]);
    }

    public function sendSms(Request $request){
        $data = $request->validate([
            'from' => 'required',
            'to' => 'required',
            'body' => 'required'
        ]);

        $smsProfile = SmsProfile::where('organization_id', auth()->user()->organization_id)->where('default', 1)->first();
        
        $data['sms_profile'] = $smsProfile;

        return FunctionCall::send_sms($data);

    }
/*


    public function process($campaignId){
        
        if(session()->has('dialer.call_id.' . auth()->user()->organization_id) == false){
            return view('dialer_campaigns.show')->with('authenticationRequired', true);
        }

        if(session()->has('dialer.client_id') == false){
            session(['dialer.client_id' => Str::uuid()]);
        }

        $client_id = session('dialer.client_id');

        $campaign = DialerCampaign::find($campaignId);
        session(['campaing_id' => $campaign->id]);

        if( !$this->inTime($campaign->start_at, $campaign->end_at, $campaign->end_date, $campaign->schedule_days) ){
            return view('dialer_campaigns.show')->with('scheduleNotMatch', true);
        }

        $contact = $this->getCampaignContact($campaign);

        if($contact){
            $content = optional($campaign->script)->content;

            if($content){
                $campaign->script_content = Script::prepare($content, $contact);
            }           
            


            return view('dialer_campaigns.popup', compact('campaign', 'contact', 'client_id'))->with([
                'total_contacts' => $this->totalContacts,
                'total_process_contacts' => $this->totalProccesContacts,
            ]);
        }
        else {
            $campaign->update(['status' => 2]);
            return view('dialer_campaigns.show')->with('campaignCompleted', true);
        }
           
    }
    public function dial(){
        $tel_no = request()->query('tel_no');
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $client_id = session('dialer.client_id');
        $campaing_id = session('campaing_id');

        $params = [
            'campaign_id' => $campaing_id,
            'dialer_call_id' => $call_id,
            'client_id' => $client_id, 
            'organization_id' => auth()->user()->organization_id
        ];

        $payload = ['to'=>$tel_no,'response'=>route('dialer_campaign.response'), 'statusCallback'=>route('dialer_campaign.responseCallback',$params)];
        $call = FunctionCall::send_call($payload);

        if(isset($call['error'])){ 
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=>3]]);
            return $call;
        }

        DialerCampaignCall::create(['dialer_campaign_id' => $campaing_id, 'call_id' => $call['call_id'], 'tel' => $tel_no, 'status' => $call['status-code']]);
        
        $voice_response = new VoiceResponse;
        $voice_response->bridge($call['call_id']);
        $voice_response->redirect(route('dialer_campaign.response'));
        FunctionCall::modify_call($call_id,['responseXml'=>$voice_response->xml()]);
        $call['error'] = false;
        return $call;
    }

    public function hangup(){
        $response = new VoiceResponse();
        $response->hangup();
        return FunctionCall::modify_call(request()->get('call_id'),['responseXml'=>$response->xml()]);
    }

    public function forward(){
        $response = new VoiceResponse();
        $response->dial(request()->query('forward'));
        $response->hangup();
        return FunctionCall::modify_call(request()->get('call_id'),['responseXml'=>$response->xml()]);
    }

    


    public function dialer_connect_response(){
        $voice_response = new VoiceResponse;
        $voice_response->pause(10);
        $voice_response->redirect(route('dialer_campaign.response'));
        return $voice_response->xml();
    }


    public function dial_status_callback($client_id){
        $calldata = request()->input();
        Log::info($calldata);

        if($calldata['bridge_call_id'] != ''){
            $call = Call::find( $calldata['bridge_call_id'] );
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>CallHandler::prepare_call_json( $call,false )]);        
        }else
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=>3]]);
        
        return $this->dialer_connect_response();
    }


    public function dialer_response_callback($client_id){
        $calldata = request()->input();
        Log::info($calldata);

        $type = 1;
        if($client_id == 'webdialer') {
            $client_id = $calldata['call_id']; 
            $type = 0; 
            if($calldata['status-code'] >= CallStatusEnum::Disconnected->value){
               // info("dialer disconnecting");
                //$this->logout();
            }
        }

        if($type == 1 && $calldata['status-code'] >= CallStatusEnum::Disconnected->value){

            if(!CallHistory::where('bridge_call_id', $calldata['call_id'])->exists()){
            
                CallHistory::create([
                    'organization_id'=> $calldata['organization_id'],
                    'call_id'=>$calldata['call_id'],
                    'bridge_call_id' => $calldata['dialer_call_id'],
                    'duration' => $calldata['duration'],
                    'record_file' => isset($calldata['record_file']) ? $calldata['record_file'] : '',
                    'status' => CallStatusEnum::fromKey($calldata['status-code'])
                ]);
                
            }

            DialerCampaignCall::updateOrCreate(['tel' => $calldata['to'], 'dialer_campaign_id' => $calldata['campaign_id'] ], [
                'dialer_campaign_id' => $calldata['campaign_id'],
                'call_id' => $calldata['call_id'],
                'tel' => $calldata['to'],
                'duration' => $calldata['duration'],
                'status' => $calldata['status-code']
            ]);
            
        }


        $data = ['type'=>$type,'data'=>$calldata];
        FunctionCall::send_to_websocket($client_id,$data);
    }

    
    
    public function endCall(Request $request){

        $outgoingCall = Call::find($request->input('call_id'));
        if(!CallHistory::where('call_id', $outgoingCall->id)->exists()){
            
            $callHistory = CallHistory::create([
                'organization_id'=>auth()->user()->organization_id,
                'call_id'=>$outgoingCall->id,
                'bridge_call_id' => isset($outgoingCall->bridge_call_id) ? $outgoingCall->bridge_call_id : "",
                'duration' => $outgoingCall->duration,
                'record_file' => $outgoingCall->record_file,
                'status' => $outgoingCall->status
            ]);
        }

        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $call = Call::find($request->input('call_id'));

        if(!CallHistory::where('call_id', $call->id)->exists()){
            CallHistory::create([
                'organization_id'=>auth()->user()->organization_id,
                'call_id'=>$call->id,
                'bridge_call_id' => isset($call->bridge_call_id) ? $call->bridge_call_id : "",
                'duration' => $call->duration,
                'record_file' => $call->record_file,
                'status' => $call->status
            ]);

        }

        return response()->json(['status' => 'success']);

    }
    */

    

}
