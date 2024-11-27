<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TtsProfile;
use App\Models\User;
use App\Models\VoiceFile;
use App\Tts\Tts;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Schema;

class VoiceFilesController extends Controller {

    /**
     * Display a listing of the voice files.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {

        $q         = $request->get( 'q' ) ?: '';
        $perPage   = $request->get( 'per_page' ) ?: 10;
        $filter    = $request->get( 'filter' ) ?: '';
        $sort      = $request->get( 'sort' ) ?: '';
        $voiceFile = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->latest();

        if (  ! empty( $q ) ) {
            $voiceFile->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $voiceFile->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $voiceFile->orderBy( $sorta[0], $sorta[1] );
        } else {
            $voiceFile->orderBy( 'id', 'DESC' );
        }

        $voiceFiles = $voiceFile->paginate( $perPage );

        $voiceFiles->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'voiceFiles.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'file_name']; // specify columns if need

            $callback = function () use ( $voiceFiles, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $voiceFiles as $voiceFile ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $voiceFile->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'voice_files.table', compact( 'voiceFiles' ) );
        }

        return view( 'voice_files.index', compact( 'voiceFiles' ) );

    }

    /**
     * Show the form for creating a new voice file.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $org_id = auth()->user()->organization_id;
        $tts_profiles = TtsProfile::where( function ($query) use ($org_id) {
            $query->where("organization_id", $org_id)
                  ->orWhere("organization_id", 0);
        } )->where('type', 0)->pluck( 'name', 'id' );
        if ( $request->ajax() ) {
            return view( 'voice_files.form', compact( 'tts_profiles' ) )->with( ['action' => route( 'voice_files.voice_file.store' ), 'voiceFile' => null, 'method' => 'POST'] );
        } else {
            return view( 'voice_files.create', compact( 'tts_profiles' ) );
        }

    }

    /**
     * Store a new voice file in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;

        if ( $request->file() ) {
            $fileName          = time() . '_' . str_replace(' ','_',$request->file->getClientOriginalName());
            $path              = storage_path( 'app/public/uploads/' . auth()->user()->organization_id );
            $filePath          = $request->file( 'file' )->storeAs( 'uploads/' . auth()->user()->organization_id, $fileName, 'public' );
            $data['file_name'] = $fileName;
        }

        $data['organization_id'] = auth()->user()->organization_id;

        VoiceFile::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'voice_files.voice_file.index' )
            ->with( 'success_message', 'Voice File was successfully added.' );

    }

    /**
     * Show the form for editing the specified voice file.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! VoiceFile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $voiceFile    = VoiceFile::findOrFail( $id );
        $org_id = auth()->user()->organization_id;
        $tts_profiles = TtsProfile::where( function ($query) use ($org_id) {
            $query->where("organization_id", $org_id)
                  ->orWhere("organization_id", 0);
        } )->where('type', 0)->pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'voice_files.form', compact( 'voiceFile', 'tts_profiles' ) )->with( ['action' => route( 'voice_files.voice_file.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'voice_files.edit', compact( 'voiceFile', 'tts_profiles' ) );
        }

    }

    /**
     * Update the specified voice file in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $request->validate( ['name' => 'required|string|min:1|max:255'] );

        $data = $request->except( ['_token', '_mehtod'] );

        if(! VoiceFile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $voiceFile = VoiceFile::findOrFail( $id );

        if ( $request->voice_type == 0 && $request->hasFile( 'file' ) ) {
            $fileName               = time() . '_' . str_replace(' ','_',$request->file->getClientOriginalName());
            $path                   = storage_path( 'app/public/uploads/' . auth()->user()->organization_id );
            $filePath               = $request->file( 'file' )->storeAs( 'uploads/' . auth()->user()->organization_id, $fileName, 'public' );
            $data['file_name']      = $fileName;
            $data['tts_profile_id'] = null;
            $data['tts_text']       = null;
        }

        if ( $request->voice_type == 1 && ! empty( $voiceFile->file_name ) ) {
            $data['file_name'] = null;
        }

        $voiceFile->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'voice_files.voice_file.index' )
            ->with( 'success_message', 'Voice File was successfully updated.' );

    }

    /**
     * Remove the specified voice file from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! VoiceFile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $voiceFile = VoiceFile::findOrFail( $id );
            $voiceFile->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'voice_files.voice_file.index' )
                    ->with( 'success_message', 'Voice File was successfully deleted.' );
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
     * update the specified voice file for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
        
            if(! VoiceFile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $voiceFile = VoiceFile::findOrFail( $id );

            $voiceFile->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified voice file for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                VoiceFile::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new VoiceFile )->getTable(), $field ) ) {
                        VoiceFile::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    public function play() {        
        $voice_file_id = request()->query('voice_file_id');
        $refresh = request()->query('refresh',false);
        $voice = VoiceFile::find( $voice_file_id );
        if ( $voice && $voice->voice_type == 1 ) {
           $path = Tts::synthesize( $voice->tts_text,auth()->user()->organization_id,$voice->tts_profile_id );            
            if($refresh  && !is_dir($path) && file_exists($path)){
                unlink($path);
                return response()->json( ['status' => true, 'path' => 'refresh'] );
            }

            $path = substr($path, strpos($path, "tts"));

            $path = asset('storage/' . $path);

            return response()->json( ['status' => true, 'path' => $path] );

        }

        if ( $voice && $voice->file_name != null ) {
            $path = asset( 'storage/uploads/' . auth()->user()->organization_id . '/' . $voice->file_name );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );
    }

    public function record(Request $request ) {

        if($request->has('path')){
            $path = asset( 'storage/' . $request->input('path') );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );

    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request ) {

        $rules['name']       = 'required|string|min:1|max:255';
        $rules['voice_type'] = 'required';

        if (  ! $request->hasFile( 'file' ) ) {
            $rules['tts_text']       = 'required|string|min:1';
            $rules['tts_profile_id'] = 'required';
        } else {
            $rules['file'] = 'required|mimes:mp3|max:20480';
        }

        $data = $request->validate( $rules );

        return $data;
    }

}
