<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Jobs\ProcessCampaign;
use App\Models\Campaign;
use App\Models\CampaignCall;
use App\Models\CampaignSms;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Func;
use App\ServiceProviders\TtsVoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Schema;

use function Psy\debug;

class BroadcastsController extends Controller {

    use FuncTrait;
    /**
     * Display a listing of the campaigns.
     *
     * @return Illuminate\View\View
     */

    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    }  
    public function index( Request $request ) {

        $q        = $request->get( 'q' ) ?: '';
        $perPage  = $request->get( 'per_page' ) ?: 10;
        $filter   = $request->get( 'filter' ) ?: '';
        $sort     = $request->get( 'sort' ) ?: '';
        $campaign = Campaign::where( 'organization_id', '=', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $campaign->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $campaign->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $campaign->orderBy( $sorta[0], $sorta[1] );
        } else {
            $campaign->orderBy( 'created_at', 'DESC' );
        }

        $campaigns = $campaign->paginate( $perPage );

        $campaigns->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'broadcasts.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'organization_id', 'email', 'campaign_type', 'voice_file_id', 'tts', 'tts_lang', 'provider_id', 'max_retry', 'call_limit', 'timezone', 'start_at', 'end_at', 'schedule_days', 'total_sent', 'total_successfull', 'total_failed', 'status']; // specify columns if need

            $callback = function () use ( $campaigns, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $campaigns as $campaign ) {

                    foreach ( $columns as $column ) {
                        $camValue = $campaign->{$column};

                        if ( is_array( $camValue ) ) {
                            $camValue = implode( ',', $camValue );
                        }

                        $row[$column] = $camValue;

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'broadcasts.table', compact( 'campaigns' ) );
        }

        return view( 'broadcasts.index', compact( 'campaigns' ) );

    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }

    /**
     * Show the form for creating a new campaign.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        $destinations = [];
        $functions    = Func::getFuncList();

        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        
        $campaign = new Campaign( ['max_retry' => 1, 'call_limit' => 3, 'start_at' => '08:00:00', 'end_at' => '18:00:00', 'schedule_days' => array_keys( config( 'enums.weekdays' ) )] );

        if ( $request->ajax() ) {
            return view( 'broadcasts.form', compact( 'functions', 'contact_groups', 'campaign', 'destinations' ) )->with( ['action' => route( 'broadcasts.broadcast.store' ), 'campaign' => null, 'method' => 'POST'] );
        } else {
            return view( 'broadcasts.create', compact( 'functions', 'contact_groups', 'campaign', 'destinations' ) );
        }

    }

    /**
     * Store a new campaign in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;
        $data['status']          = 0;
        $function                = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();
        $data['function_id']     = $function->id;

        Campaign::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'broadcasts.broadcast.index' )
            ->with( 'success_message', 'Campaign was successfully added.' );

    }

    /**
     * Show the form for editing the specified campaign.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! Campaign::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $campaign = Campaign::with( 'func' )->findOrFail( $id );

        $processCampaign = new ProcessCampaign( $id );

        $functions      = Func::getFuncList();
        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $destinations = $this->dist_by_function( $campaign->func->func, 0, true );

        if ( $request->ajax() ) {
            return view( 'broadcasts.form', compact( 'campaign', 'contact_groups', 'functions', 'destinations' ) )->with( ['action' => route( 'broadcasts.broadcast.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'broadcasts.edit', compact( 'campaign', 'contact_groups', 'functions', 'destinations' ) );
        }

    }

    /**
     * Update the specified campaign in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data                = $this->getData( $request );
        $function            = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();
        $data['function_id'] = $function->id;

        if(! Campaign::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $campaign = Campaign::findOrFail( $id );
        $campaign->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'broadcasts.broadcast.index' )
            ->with( 'success_message', 'Campaign was successfully updated.' );

    }



    /**
     * Remove the specified campaign from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            
            if(! Campaign::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $campaign = Campaign::findOrFail( $id );

            if ( $campaign->status == 1 ) {
                throw new \ErrorException( 'Running broadcast can be deleted.' );
            } 

            CampaignCall::where('campaign_id',$id)->delete();
            CampaignSms::where('campaign_id',$id)->delete();
            
            $campaign->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'broadcasts.broadcast.index' )
                    ->with( 'success_message', 'Campaign was successfully deleted.' );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()->withInput()
                    ->withErrors( ['unexpected_error' => 'Unexpected error occurred while trying to process your request.'] );
            }

        }

    }

    /**
     * update the specified campaign for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            $campaign = Campaign::findOrFail( $id );

            $campaign->update( $request->all() );

            if ( $request->has( 'status' ) && $request->input( 'status' ) == 1 ) {
                dispatch( new ProcessCampaign( $id ) )->delay( now()->addSeconds( 10 ) );
                Log::debug( 'Campaign job in queue' );

                //dispatch( new ProcessCampaignCall( $id ) )->delay( now()->addSeconds( 30 ) );
                //Log::debug( 'CampaignCall Job in queue.' );
            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified campaign for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Campaign::whereIn( 'id', $ids )->where( 'status','!=', '1' )->delete();
                CampaignCall::whereIn('campaign_id',$ids)->delete();
                CampaignSms::whereIn('campaign_id',$ids)->delete();
            } else {
                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Campaign )->getTable(), $field ) ) {
                        Campaign::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    public function getVoiceFile( $id ) {
        $campaign = Campaign::findOrFail( $id );

        $ttsvoice = new TtsVoice;
        $tts      = ['text' => $campaign->tts, 'lang' => $campaign->tts_lang, 'gender' => $campaign->tts_gender, 'auth_id' => $campaign->organization_id];
        $file     = $ttsvoice->generate( $tts, false );

        $headers = [
            'Content-Type: audio/mpeg',
        ];

        return response()->download( $file, $id . '.mp3', $headers );

    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request ) {

        $rules = [
            'name'           => 'required|string|min:1|max:255',
            'from'           => 'required|string|min:1|max:255',
            'contact_groups' => 'required|array|min:1|max:100',
            'max_retry'      => 'required|numeric|min:0|max:2147483647',
            'call_limit'     => 'required|numeric|min:0|max:2147483647',
            'timezone'       => 'required|string|min:1|max:100',
            'start_at'       => 'required|date_format:H:i',
            'end_at'         => 'required|date_format:H:i',
            'schedule_days'  => 'required|array|min:1|max:100',
            'function_id'    => 'required|string|min:1|max:100',
            'destination_id' => 'required|numeric|min:0|max:2147483647',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

    public function contacts( $campaign ): array | object {
        $contacts = Contact::select( [DB::raw( "CONCAT(cc,tel_no) as tel" )] );

        foreach ( $campaign->contact_groups as $key => $groupId ) {

            $statement = $key === 0 ? 'whereRaw' : 'orWhereRaw';
            $contacts->{$statement}

            ( 'FIND_IN_SET(?, contact_groups)', [$groupId] );

        }

        return $contacts->groupBy( 'tel' )->pluck( 'tel' )->toArray();
    }

}
