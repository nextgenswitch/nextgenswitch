<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VoiceFile;
use App\Models\VoiceRecord;
use Illuminate\Http\Request;
use Exception;
use Schema;

class VoiceRecordsController extends Controller
{


    public function __construct(){
        config(['menu.group' => 'menu-application']);  
    } 
    /**
     * Display a listing of the voice records.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $voiceRecord = VoiceRecord::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $voiceRecord->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $voiceRecord->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $voiceRecord->orderBy($sorta[0],$sorta[1]);
        }else
            $voiceRecord->orderBy('created_at','DESC'); 
        

        $voiceRecords = $voiceRecord->paginate($perPage);
        
        $voiceRecords->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'voiceRecords.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new VoiceRecord)->getTable());  
               
                $callback = function() use($voiceRecords, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($voiceRecords as $voiceRecord) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $voiceRecord->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('voice_records.table', compact('voiceRecords'));
        } 
        
        return view('voice_records.index', compact('voiceRecords'));


    }

    /**
     * Show the form for creating a new voice record.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $voices = VoiceFile::pluck('name','id')->all();
        
        if($request->ajax())
            return view('voice_records.form', compact('voices'))->with(['action'=>route('voice_records.voice_record.store'),'voiceRecord' => null,'method'=>'POST']);
        else
            return view('voice_records.create', compact('voices'));
    }

    /**
     * Store a new voice record in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            
            VoiceRecord::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('voice_records.voice_record.index')
                ->with('success_message', __('Voice Record was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified voice record.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $voiceRecord = VoiceRecord::findOrFail($id);
        $voices = VoiceFile::pluck('name','id')->all();

        if($request->ajax())
            return view('voice_records.form', compact('voiceRecord','voices'))->with(['action'=>route('voice_records.voice_record.update',$id),'method'=>'PUT']);
        else
            return view('voice_records.edit', compact('voiceRecord','voices'));
    }

    /**
     * Update the specified voice record in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
            $data = $this->getData($request);
            
            $voiceRecord = VoiceRecord::findOrFail($id);
            $voiceRecord->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('voice_records.voice_record.index')
                ->with('success_message', __('Voice Record was successfully updated.'));
            
    }

    /**
     * Remove the specified voice record from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $voiceRecord = VoiceRecord::findOrFail($id);
            $voiceRecord->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('voice_records.voice_record.index')
                ->with('success_message', __('Voice Record was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified voice record for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $voiceRecord = VoiceRecord::findOrFail($id);  
          
            $voiceRecord->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified voice record for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                VoiceRecord::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new VoiceRecord)->getTable(), $field) )
                          VoiceRecord::whereIn('id',$ids)->update([$field=>$val]);
                }
            }
            return response()->json(['success'=>true]);

       } catch (Exception $exception) {
            return response()->json(['success'=>false]);
       }

       
    }    



    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1|max:191',
            'voice_id' => 'required',
            'is_transcript' => 'boolean|nullable',
            'play_beep' => 'boolean',
            'is_create_ticket' => 'boolean',
            'email' => 'nullable|string|min:0|max:191', 
            'phone' => 'nullable|string', 
        ];

        
        $data = $request->validate($rules);

        $data['is_transcript'] = $request->has('is_transcript');
        $data['play_beep'] = $request->has('play_beep');
        
        return $data;
    }

}
