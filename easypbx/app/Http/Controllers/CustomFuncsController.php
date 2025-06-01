<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Controller;
use App\Models\CustomFunc;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Api\FunctionCall;
use Schema;

class CustomFuncsController extends Controller {

    public function __construct(){
        config(['menu.group' => 'menu-application']);  
    } 
    public function function_execute( $function_id ) {
        $custom_func = CustomFunc::find( $function_id );

        if ( $custom_func->func_lang == 1 ) {
            return response( $custom_func->func_body, 200 )->header( 'Content-Type', 'application/xml' );
        } elseif ( $custom_func->func_lang == 2 ) {
            $path     = storage_path( 'app/funcs' );
            $filename = md5( $custom_func->func_body );

            if (  ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }

            $path = $path . '/' . $filename . '.blade.php';

            if (  ! file_exists( $path ) ) {
                $content =  $custom_func->func_body;
                File::put( $path, $content );
            }

            $response = new VoiceResponse();
            include_once $path;
            
            return response( $response->xml(), 200 )->header( 'Content-Type', 'application/xml' );
        }

    }

    /**
     * Display a listing of the custom funcs.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {

        $q          = $request->get( 'q' ) ?: '';
        $perPage    = $request->get( 'per_page' ) ?: 10;
        $filter     = $request->get( 'filter' ) ?: '';
        $sort       = $request->get( 'sort' ) ?: '';
        $customFunc = CustomFunc::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $customFunc->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $customFunc->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $customFunc->orderBy( $sorta[0], $sorta[1] );
        } else {
            $customFunc->orderBy( 'created_at', 'DESC' );
        }

        $customFuncs = $customFunc->paginate( $perPage );

        $customFuncs->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'customFuncs.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing(  ( new CustomFunc )->getTable() );

            $callback = function () use ( $customFuncs, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $customFuncs as $customFunc ) {

//$row['Title']  = $task->title;

                    foreach ( $columns as $column ) {
                        $row[$column] = $customFunc->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'custom_funcs.table', compact( 'customFuncs' ) );
        }

        return view( 'custom_funcs.index', compact( 'customFuncs' ) );

    }

    /**
     * Show the form for creating a new custom func.
     *
     * @return Illuminate\View\View
     */
    public function create($func_lang, Request $request ) {
        $customFunc = new CustomFunc;
        $customFunc->func_lang = $func_lang;
        if ( $request->ajax() ) {
            return view( 'custom_funcs.form' )->with( ['action' => route( 'custom_funcs.custom_func.store' ), 'customFunc' => null, 'method' => 'POST'] );
        } else {
            return view( 'custom_funcs.create' ,compact( 'customFunc' ) );
        }

    }

    /**
     * Store a new custom func in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;

        CustomFunc::create( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'custom_funcs.custom_func.index' )
            ->with( 'success_message', __( 'Custom Function was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified custom func.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! CustomFunc::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $customFunc = CustomFunc::findOrFail( $id );

        if ( $request->ajax() ) {
            return view( 'custom_funcs.form', compact( 'customFunc' ) )->with( ['action' => route( 'custom_funcs.custom_func.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'custom_funcs.edit', compact( 'customFunc' ) );
        }

    }

    /**
     * Update the specified custom func in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        if(! CustomFunc::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
        $customFunc = CustomFunc::findOrFail( $id );
        
        

        $customFunc->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'custom_funcs.custom_func.index' )
            ->with( 'success_message', __( 'Custom Function was successfully updated.' ) );

    }

    /**
     * Remove the specified custom func from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! CustomFunc::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $customFunc = CustomFunc::findOrFail( $id );
            $customFunc->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'custom_funcs.custom_func.index' )
                    ->with( 'success_message', __( 'Custom Function was successfully deleted.' ) );
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
     * update the specified custom func for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! CustomFunc::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $customFunc = CustomFunc::findOrFail( $id );

            $customFunc->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified custom func for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                CustomFunc::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new CustomFunc )->getTable(), $field ) ) {
                        CustomFunc::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'name'      => 'required',
            'func_lang' => 'required|string|min:1',
            'func_body' => 'required',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
