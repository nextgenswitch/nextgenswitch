<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Script;
use Exception;
use Illuminate\Http\Request;
use Schema;

class ScriptsController extends Controller {

    /**
     * Display a listing of the scripts.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 
    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $script  = Script::where('organization_id', auth()->user()->organization_id);

        if (  ! empty( $q ) ) {
            $script->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $script->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $script->orderBy( $sorta[0], $sorta[1] );
        } else {
            $script->orderBy( 'created_at', 'DESC' );
        }

        $scripts = $script->paginate( $perPage );

        $scripts->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'scripts.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name','content']; // specify columns if need
            // $columns = Schema::getColumnListing( ( new Script )->getTable() );

            $callback = function () use ( $scripts, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $scripts as $script ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $script->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'scripts.table', compact( 'scripts' ) );
        }

        return view( 'scripts.index', compact( 'scripts' ) );

    }

    /**
     * Show the form for creating a new script.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        

        if ( $request->ajax() ) {
            return view( 'scripts.form' )->with( ['action' => route( 'scripts.script.store' ), 'script' => null, 'method' => 'POST'] );
        } else {
            return view( 'scripts.create');
        }

    }

    /**
     * Store a new script in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;

        Script::create( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'scripts.script.index' )
            ->with( 'success_message', __( 'Script was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified script.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        $script        = Script::findOrFail( $id );
        

        if ( $request->ajax() ) {
            return view( 'scripts.form', compact( 'script' ) )->with( ['action' => route( 'scripts.script.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'scripts.edit', compact( 'script' ) );
        }

    }

    /**
     * Update the specified script in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        $script = Script::findOrFail( $id );
        $script->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'scripts.script.index' )
            ->with( 'success_message', __( 'Script was successfully updated.' ) );

    }

    /**
     * Remove the specified script from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            $script = Script::findOrFail( $id );
            $script->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'scripts.script.index' )
                    ->with( 'success_message', __( 'Script was successfully deleted.' ) );
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
     * update the specified script for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            $script = Script::findOrFail( $id );

            $script->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified script for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Script::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn( ( new Script )->getTable(), $field ) ) {
                        Script::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'content' => 'required|string|min:1|max:16777215',
            'name'    => 'required|string|min:1|max:191',

        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
