<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Stream;
use App\Models\Func;
use App\Http\Traits\FuncTrait;
use Illuminate\Support\Facades\Auth;


class StreamController extends Controller
{
    use FuncTrait;

    public function __construct(){
        config(['menu.group' => 'menu-application']);  
    } 
    public function index(Request $request)
    {
        $q       = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter  = $request->get('filter') ?: '';
        $sort    = $request->get('sort') ?: '';
        $streams = Stream::where('organization_id', Auth::user()->organization_id);

        if (!empty($q)) {
            $streams->where('name', 'LIKE', '%' . $q . '%');
        }
        if (!empty($filter)) {
            $filtera = explode(':', $filter);
            $streams->where($filtera[0], '=', $filtera[1]);
        }
        if (!empty($sort)) {
            $sorta = explode(':', $sort);
            $streams->orderBy($sorta[0], $sorta[1]);
        } else {
            $streams->orderBy('created_at', 'DESC');
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName   = 'streams.csv';
            $streams = $streams->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];


            $columns = ['name','ws_url','prompt', 'forwarding_number', 'email']; // specify columns if need

            $callback = function () use ( $streams, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $streams as $stream ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $stream->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $streams = $streams->paginate($perPage);
        $streams->appends(['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('streams.table', compact('streams'));
        }
        return view('streams.index', compact('streams'));
    }


    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }


    public function create(Request $request)
    {
        $functions    = Func::getFuncList();
        $destinations = [];

        if ( $request->ajax() ) {
            return view( 'streams.form', compact('functions', 'destinations') )->with( ['action' => route( 'streams.stream.store' ), 'stream' => null, 'method' => 'POST'] );
        } else {
            return view( 'streams.create', compact('functions', 'destinations') );
        }
    }

    public function store(Request $request)
    {
        $data = $this->getData($request);
       
        $func = Func::select('id')->where('func', $data['function_id'])->first();
        $data['function_id'] = $func->id;
        
        $data['organization_id'] = Auth::user()->organization_id;
        $stream = Stream::create($data);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('streams.stream.index')->with('success_message', __('Stream was successfully added.'));
    }

    public function show($id)
    {
        $stream = Stream::findOrFail($id);
        return view('streams.show', compact('stream'));
    }

    public function edit($id, Request $request)
    {
        if(! Stream::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
        $stream = Stream::findOrFail( $id );

        $functions = Func::getFuncList();
        $destinations = $this->dist_by_function( $stream->function->func, 0, true );

        
        
        if ( $request->ajax() ) {
            return view( 'streams.form', compact( 'stream', 'functions', 'destinations' ) )->with( ['action' => route( 'streams.stream.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'streams.edit', compact( 'stream' , 'functions', 'destinations') );
        }
    }

    public function update($id, Request $request)
    {
        if(! Stream::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $data = $this->getData($request);
        $data['record'] = $request->has('record') ? $request->get('record') : 0;
        $func = Func::select('id')->where('func', $data['function_id'])->first();
        $data['function_id'] = $func->id;

        $stream = Stream::findOrFail($id);
        $stream->update($data);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('streams.stream.index')->with('success_message', __('Stream was successfully updated.'));
    }

    public function destroy($id, Request $request)
    {
        $stream = Stream::findOrFail($id);
        $stream->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('streams.stream.index')->with('success_message', __('Stream was successfully deleted.'));
    }

    public function updateField($id,Request $request){
          
        try {
            
            if(! Stream::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $stream = Stream::findOrFail($id);  
          
            $stream->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified sms for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Stream::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Stream )->getTable(), $field ) ) {
                        Stream::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1|max:191',
            'ws_url' => 'required|string',
            'prompt' => 'nullable|string',
            'greetings' => 'nullable|string',
            'extra_parameters' => 'nullable|string',
            'max_call_duration' => 'nullable|integer',
            'record' => 'nullable|boolean',
            'forwarding_number' => 'nullable|string',
            'email' => 'nullable|email',
            'function_id'    => 'required|string|min:1|max:255',
            'destination_id' => 'required|integer',
        ];
        return $request->validate($rules);
    }
}
