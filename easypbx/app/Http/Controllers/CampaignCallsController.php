<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Campaign;
use App\Models\CampaignCall;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Schema;

class CampaignCallsController extends Controller {

    /**
     * Display a listing of the campaign calls.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request) {
        $campaignId = $request->query('id');
        if(empty($campaignId)){
            $campaign = Campaign::where("organization_id",auth()->user()->organization_id)->first();
            if($campaign)
                $campaignId = $campaign->id;
            else
                return redirect()->route( 'campaigns.campaign.index' );
        }
        if ( $campaignId == 0 ) {
            $campaign = Campaign::where( 'organization_id', '=', auth()->user()->organization_id )->first();

            if ( $campaign ) {
                return redirect()->route( 'campaign_calls.campaign_call.index', $campaign->id );
            } else {
                return back();
            }

        }

        $campaign = Campaign::where( 'organization_id', '=', auth()->user()->organization_id )->findOrFail( $campaignId );
        $q        = $request->get( 'q' ) ?: '';
        $perPage  = $request->get( 'per_page' ) ?: 10;
        $filter   = $request->get( 'filter' ) ?: '';
        $sort     = $request->get( 'sort' ) ?: '';

        $campaignCall = CampaignCall::with( ['campaign', 'call'] )->where( 'campaign_id', '=', $campaignId );

        if (  ! empty( $q ) ) {
            $campaignCall->where( 'tel_no', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $campaignCall->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $campaignCall->orderBy( $sorta[0], $sorta[1] );
        } else {
            $campaignCall->orderBy( 'created_at', 'DESC' );
        }

        $campaignCalls = $campaignCall->paginate( $perPage );

        $campaignCalls->appends( ['id'=>$campaignId,'sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'campaignCalls.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            // specify columns if need
            $columns = ['call_id', 'call_type', 'call_sid', 'user_id', 'campaign_id', 'tel_no', 'cost', 'duration', 'last_try', 'num_try', 'status', 'error_code'];

            $callback = function () use ( $campaignCalls, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $campaignCalls as $campaignCall ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $campaignCall->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $campaigns = Campaign::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'campaign_calls.table', compact( 'campaignCalls', 'campaign', 'campaigns' ) );
        }

        return view( 'campaign_calls.index', compact( 'campaignCalls', 'campaign', 'campaigns' ) );

    }

    /**
     * Show the form for creating a new campaign call.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $calls     = Call::pluck( 'id', 'id' )->all();
        $users     = User::pluck( 'name', 'id' )->all();
        $campaigns = Campaign::pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'campaign_calls.form', compact( 'calls', 'users', 'campaigns' ) )->with( ['action' => route( 'campaign_calls.campaign_call.store' ), 'campaignCall' => null, 'method' => 'POST'] );
        } else {
            return view( 'campaign_calls.create', compact( 'calls', 'users', 'campaigns' ) );
        }

    }

    /**
     * Store a new campaign call in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        CampaignCall::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'campaign_calls.campaign_call.index' )
            ->with( 'success_message', 'Campaign Call was successfully added.' );

    }

    /**
     * Show the form for editing the specified campaign call.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        if(! CampaignCall::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $campaignCall = CampaignCall::findOrFail( $id );
        $calls        = Call::pluck( 'id', 'id' )->all();
        $users        = User::pluck( 'name', 'id' )->all();
        $campaigns    = Campaign::pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'campaign_calls.form', compact( 'campaignCall', 'calls', 'users', 'campaigns' ) )->with( ['action' => route( 'campaign_calls.campaign_call.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'campaign_calls.edit', compact( 'campaignCall', 'calls', 'users', 'campaigns' ) );
        }

    }

    /**
     * Update the specified campaign call in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        if(! CampaignCall::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $campaignCall = CampaignCall::findOrFail( $id );
        $campaignCall->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'campaign_calls.campaign_call.index' )
            ->with( 'success_message', 'Campaign Call was successfully updated.' );

    }

    /**
     * Remove the specified campaign call from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! CampaignCall::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $campaignCall = CampaignCall::findOrFail( $id );
            $campaignCall->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'campaign_calls.campaign_call.index' )
                    ->with( 'success_message', 'Campaign Call was successfully deleted.' );
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
     * update the specified campaign call for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! CampaignCall::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $campaignCall = CampaignCall::findOrFail( $id );

            $campaignCall->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified campaign call for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                CampaignCall::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new CampaignCall )->getTable(), $field ) ) {
                        CampaignCall::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'call_id'     => 'required',
            'call_sid'    => 'required|string|min:1|max:100',
            'user_id'     => 'required',
            'campaign_id' => 'required',
            'tel_no'      => 'required|string|min:1|max:100',
            'status'      => 'required|numeric|min:-2147483648|max:2147483647',
            'duration'    => 'required|numeric|min:-2147483648|max:2147483647',
            'last_try'    => 'nullable|date_format:j/n/Y g:i A',
            'num_try'     => 'nullable|string|min:0',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
