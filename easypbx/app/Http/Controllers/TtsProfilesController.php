<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\TtsHistory;
use App\Models\TtsProfile;
use Exception;
use Illuminate\Http\Request;
use Schema;
use stdClass;

class TtsProfilesController extends Controller {
    /**
     * Display a listing of the tts profiles.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {
        $q          = $request->get( 'q' ) ?: '';
        $perPage    = $request->get( 'per_page' ) ?: 10;
        $filter     = $request->get( 'filter' ) ?: '';
        $sort       = $request->get( 'sort' ) ?: '';
        $ttsProfile = TtsProfile::where( 'organization_id', auth()->user()->organization_id );
       // $ttsProfile = TtsProfile::latest();
        if (  ! empty( $q ) ) {
            $ttsProfile->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $ttsProfile->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $ttsProfile->orderBy( $sorta[0], $sorta[1] );
        } else {
            $ttsProfile->orderBy( 'created_at', 'DESC' );
        }

        $ttsProfiles = $ttsProfile->paginate( $perPage );

        $ttsProfiles->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );


        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'ttsProfiles.csv';

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing(  ( new TtsProfile() )->getTable() );

            $callback = function () use ( $ttsProfiles, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $ttsProfiles as $ttsProfile ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $ttsProfile->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'tts_profiles.table', compact( 'ttsProfiles' ) );
        }

        return view( 'tts_profiles.index', compact( 'ttsProfiles' ) );
    }


    public function histories(Request $request, $profile_id){
        
        $q          = $request->get( 'q' ) ?: '';
        $perPage    = $request->get( 'per_page' ) ?: 10;
        $filter     = $request->get( 'filter' ) ?: '';
        $sort       = $request->get( 'sort' ) ?: '';

        $histories = TtsHistory::where('tts_profile_id', $profile_id);

        
        if (  ! empty( $q ) ) {
            $histories->where( 'output', 'LIKE', '%' . $q . '%' );
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

        // return $histories->get();
        $histories = $histories->paginate( $perPage );

        $histories->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'tts_profiles.histories.table', compact( 'histories' ) );
        }

        return view( 'tts_profiles.histories.index', compact( 'histories' ) );

    }


    /**
     * Show the form for creating a new tts profile.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request , $type) {
        $organizations = Organization::pluck( 'name', 'id' );
        $ttsProfile = new stdClass;

        $ttsProfile->type = $type;

        if ( $request->ajax() ) {
            return view( 'tts_profiles.form', compact( 'organizations', 'ttsProfile' ) )->with( ['action' => route( 'tts_profiles.tts_profile.store' ), 'method' => 'POST'] );
        } else {
            return view( 'tts_profiles.create', compact( 'organizations', 'ttsProfile' ) );
        }

    }

    /**
     * Store a new tts profile in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );
         $data['organization_id'] = auth()->user()->organization_id;

        $request->validate( [$data['provider'] . '.*' => 'required'] );

        if ( $data['provider'] == 'google_cloud' ) {

            $data['config'] = $request->input($data['provider'])['config'];

        } else {
            $data['config'] = json_encode( $request->input($data['provider']) );
        }
        if($data['organization_id'] == '') $data['organization_id'] = 0;

        TtsProfile::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()
            ->route( 'tts_profiles.tts_profile.index' )
            ->with( 'success_message', __( 'Tts Profile was successfully added.' ) );
    }

    /**
     * Show the form for editing the specified tts profile.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! TtsProfile::where('id', $id)->exists() )
            return back();

        $ttsProfile = TtsProfile::findOrFail( $id );
        $ttsProfile[$ttsProfile->provider] = json_decode($ttsProfile->config);

        $organizations = Organization::pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'tts_profiles.form', compact( 'ttsProfile', 'organizations' ) )->with( ['action' => route( 'tts_profiles.tts_profile.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'tts_profiles.edit', compact( 'ttsProfile', 'organizations' ) );
        }

    }

    /**
     * Update the specified tts profile in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {
        $data = $this->getData( $request , 1);

        if(! TtsProfile::where('id', $id)->exists() )
            return back();

        $ttsProfile = TtsProfile::findOrFail( $id );

        
        $request->validate( [$data['provider'] . '.*' => 'required'] );


        if ( $data['provider'] == 'google_cloud' ) {

            $data['config'] = $request->input($data['provider'])['config'];

        } else {
            $data['config'] = json_encode( $request->input($data['provider']) );
        }

       // if($data['organization_id'] == '') $data['organization_id'] = 0;
       $data['organization_id'] = auth()->user()->organization_id;
        $data['is_default'] = $request->has('is_default') ? 1 : null;

        $ttsProfile->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()
            ->route( 'tts_profiles.tts_profile.index' )
            ->with( 'success_message', __( 'Tts Profile was successfully updated.' ) );
    }

    /**
     * Remove the specified tts profile from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! TtsProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $ttsProfile = TtsProfile::findOrFail( $id );
            $ttsProfile->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()
                    ->route( 'tts_profiles.tts_profile.index' )
                    ->with( 'success_message', __( 'Tts Profile was successfully deleted.' ) );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()
                    ->withInput()
                    ->withErrors( ['unexpected_error' => __( 'Unexpected error occurred while trying to process your request.' )] );
            }

        }

    }

    /**
     * update the specified tts profile for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {
		
		
        try {
            
            $ttsProfile = TtsProfile::findOrFail( $id );

            $ttsProfile->update( $request->all() );

            return response()->json( ['success' => true] );
        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified tts profile for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request) {
        try {
            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                TtsProfile::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new TtsProfile() )->getTable(), $field ) ) {
                        TtsProfile::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );
        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }
	
	public function bulkActionHistory( Request $request ) {
        try {
            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                TtsHistory::whereIn( 'id', $ids )->delete();
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
    protected function getData( Request $request , $id = 0) {
        $rules = [
            'language'        => 'nullable|string|min:1|max:255',
            'provider'        => 'required|string|min:1|max:255',
            'model'           => 'nullable|string|min:1|max:255',
            'name'            => 'required|string|min:1|max:255',
            'organization_id' => 'nullable|integer|min:1',
            'is_default'      => 'nullable|string',
            'type'             => 'required|integer'
        ];

        if($id > 0){
            $rules['provider'] = 'nullable|string|min:1|max:255';
        }

        $data = $request->validate( $rules );

        $data['neural'] = $request->has( 'neural' );

        return $data;
    }

}
