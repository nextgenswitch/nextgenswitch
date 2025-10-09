<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Func;
use App\Models\Ivr;
use App\Models\IvrAction;
use App\Models\VoiceFile;
use Exception;
use Illuminate\Http\Request;
use Schema;

class IvrsController extends Controller {

    use FuncTrait;

    /**
     * Display a listing of the ivrs.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-incoming']);  
    } 
    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $ivr     = Ivr::with( ['actions'] )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $ivr->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $ivr->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $ivr->orderBy( $sorta[0], $sorta[1] );
        } else {
            $ivr->orderBy( 'created_at', 'DESC' );
        }

        /*
        if (  ! empty( $request->get( 'csv' ) ) ) {

        $fileName = 'ivrs.csv';

        $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0",
        ];

        //$column = ['name','email','password']; // specify columns if need
        $columns = Schema::getColumnListing(  ( new Ivr )->getTable() );

        $callback = function () use ( $ivrs, $columns ) {
        $file = fopen( 'php://output', 'w' );
        fputcsv( $file, $columns );

        foreach ( $ivrs as $ivr ) {

        foreach ( $columns as $column ) {
        $row[$column] = $ivr->{$column};
        }

        fputcsv( $file, $row );
        }

        fclose( $file );
        };

        return response()->stream( $callback, 200, $headers );
        }
         */

        $ivrs = $ivr->paginate( $perPage );

        $ivrs->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'ivrs.table', compact( 'ivrs' ) );
        }

        return view( 'ivrs.index', compact( 'ivrs' ) );

    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }

    /**
     * Show the form for creating a new ivr.
     *
     * @return Illuminate\View\View
     */

// public function create( Request $request ) {

//     $voiceFiles = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );

//     if ( $request->ajax() ) {

//         return view( 'ivrs.form', compact( 'voiceFiles' ) )->with( ['action' => route( 'ivrs.ivr.store' ), 'ivr' => null, 'method' => 'POST'] );

//     } else {

//         return view( 'ivrs.tab', compact( 'voiceFiles' ) );

//     }

    // }

    public function create( Request $request ) {
        $ivr             = [];
        $ivrDestinations = [];
        $destinations    = [];
        $digits          = IvrAction::ivr_digits( 0 );
        $functions       = Func::getFuncList();

        $voiceFiles = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'ivrs.form', compact( 'voiceFiles' ) )->with( ['action' => route( 'ivrs.ivr.store' ), 'ivr' => null, 'method' => 'POST'] );
        } else {
            return view( 'ivrs.create', compact( 'ivr', 'voiceFiles', 'ivrDestinations', 'digits', 'functions', 'destinations' ) );
        }

    }

    /**
     * Store a new ivr in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $this->getData( $request );

        $ivr_data                    = $request->except( ['_token', 'action'] );
        $ivr_data['organization_id'] = auth()->user()->organization_id;

        $function                = Func::select( 'id' )->where( 'func', $ivr_data['function_id'] )->first();
        $ivr_data['function_id'] = $function->id;

        $ivr = Ivr::create( $ivr_data );

        $actions = $request->input( 'actions' );

        if ( count( $actions['digit'] ) && $actions['digit'][0] != null ) {

            foreach ( $actions['digit'] as $key => $digit ) {

                if (  ! isset( $actions['destination_id'][$key] ) || ! isset( $actions['function_id'][$key] ) ) {
                    continue;
                }

                $action['organization_id'] = auth()->user()->organization_id;
                $action['ivr_id']          = $ivr->id;
                $action['digit']           = $digit;
                $action['destination_id']  = $actions['destination_id'][$key];

                $func                  = Func::select( 'id' )->where( 'func', $actions['function_id'][$key] )->first();
                $action['function_id'] = $func->id;

                IvrAction::create( $action );
            }

        }

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'ivrs.ivr.index' )
            ->with( 'success_message', __( 'Ivr was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified ivr.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if (  ! Ivr::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $ivr = Ivr::with( ['actions' => fn( $query ) => $query->with( 'func' )] )
            ->findOrFail( $id );

        $destinations = [];

        foreach ( $ivr->actions as $action ) {
            $destinations[$action->func->func] = $this->dist_by_function( $action->func->func, $ivr->id, true );
        }

        $ivrDestinations = $this->dist_by_function( $ivr->func->func, $ivr->id, true );

        $functions  = Func::getFuncList();
        $voiceFiles = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'ivrs.form', compact( 'ivr', 'voiceFiles', 'ivrDestinations' ) )->with( ['action' => route( 'ivrs.ivr.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'ivrs.edit', compact( 'ivr', 'voiceFiles', 'functions', 'destinations', 'ivrDestinations' ) );
        }

    }

    /**
     * Update the specified ivr in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $this->getData( $request );

        $ivr_data = $request->except( ['_token', 'action'] );

        if (  ! Ivr::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $ivr = Ivr::findOrFail( $id );

        $function                = Func::select( 'id' )->where( 'func', $ivr_data['function_id'] )->first();
        $ivr_data['function_id'] = $function->id;

        $ivr->update( $ivr_data );

        IvrAction::where( 'ivr_id', $ivr->id )->delete();

        $actions = $request->input( 'actions' );

        if ( count( $actions['digit'] ) && $actions['digit'][0] != null ) {

            foreach ( $actions['digit'] as $key => $digit ) {

                if (  ! isset( $actions['destination_id'][$key] ) || ! isset( $actions['function_id'][$key] ) ) {
                    continue;
                }

                $action['organization_id'] = auth()->user()->organization_id;
                $action['ivr_id']          = $ivr->id;
                $action['digit']           = $digit;
                $action['destination_id']  = $actions['destination_id'][$key];
                $action['voice']           = isset($actions['voice'][$key]) ? $actions['voice'][$key] : '';

                $func                  = Func::select( 'id' )->where( 'func', $actions['function_id'][$key] )->first();
                $action['function_id'] = $func->id;

                IvrAction::create( $action );
            }

        }

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'ivrs.ivr.index' )
            ->with( 'success_message', __( 'Ivr was successfully updated.' ) );

    }

    /**
     * Remove the specified ivr from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {

            if (  ! Ivr::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
                return back();
            }

            $ivr = Ivr::findOrFail( $id );
            IvrAction::where( 'ivr_id', $ivr->id )->delete();
            $ivr->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'ivrs.ivr.index' )
                    ->with( 'success_message', __( 'Ivr was successfully deleted.' ) );
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
     * update the specified ivr for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {

            if (  ! Ivr::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
                return back();
            }

            $ivr = Ivr::findOrFail( $id );

            $ivr->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified ivr for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                $ivr_ids = Ivr::whereIn( 'id', $ids )->pluck( 'id' )->toArray();
                IvrAction::whereIn( 'ivr_id', $ivr_ids )->delete(); 
                Ivr::whereIn( 'id', $ids )->delete();

            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Ivr )->getTable(), $field ) ) {
                        Ivr::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'name'                     => 'required|string|min:1|max:255',
            'welcome_voice'            => 'nullable',
            'instruction_voice'        => 'required',
            'invalid_voice'            => 'required',
            'timeout_voice'            => 'required',
            'function_id'              => 'required',
            'destination_id'           => 'required',
            'timeout'                  => 'required',
            'mode'                     => 'required',
            'actions.digit.*'          => 'nullable|integer',
            'actions.function_id.*'    => 'nullable|string',
            'actions.destination_id.*' => 'nullable|integer',
            'actions.voice.*'          => 'nullable|string',

        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
