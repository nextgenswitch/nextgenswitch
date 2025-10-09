<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallParking;
use App\Models\Extension;
use Illuminate\Http\Request;
use App\Http\Traits\FuncTrait;
use App\Models\Func;
use App\Models\VoiceFile;
use Exception;
use Schema;
use App\Http\Controllers\Api\Functions\SwitchHandler;

class CallParkingsController extends Controller
{
    use FuncTrait;

    public function __construct(){
        config(['menu.group' => 'menu-application']);  
    } 
    

    /**
     * Display a listing of the call parkings.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
        // SwitchHandler::config([]);
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $callParking = CallParking::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $callParking->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $callParking->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $callParking->orderBy($sorta[0],$sorta[1]);
        }else
            $callParking->orderBy('created_at','DESC'); 
        

        $callParkings = $callParking->paginate($perPage);
        
        $callParkings->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'callParkings.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new CallParking)->getTable());  
               
                $callback = function() use($callParkings, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($callParkings as $callParking) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $callParking->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('call_parkings.table', compact('callParkings'));
        } 
        
        return view('call_parkings.index', compact('callParkings'));


    }

    
    public function destinations($function){

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }           

        die();

    }


    /**
     * Show the form for creating a new call parking.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        $functions = Func::getFuncList();
        $destinations = array();
       // $extension_code = Extension::generateUniqueCode();

        if($request->ajax())
            return view('call_parkings.form', compact('functions','destinations', 'voices'))->with(['action'=>route('call_parkings.call_parking.store'),'callParking' => null,'method'=>'POST']);
        else
            return view('call_parkings.create', compact('functions','destinations', 'voices', ));
    }

    /**
     * Store a new call parking in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization->id;
            $data['record']  = $request->has('record');
            
            $func = Func::select('id')->where('func', $data['function_id'])->first();

            $data['function_id'] = $func->id;

            $cparking = CallParking::create($data);

            if($cparking){
                $cparking->addExtensions();
            }

            // $func = Func::select('id')->where('func', 'call_parking')->first();
            // for($i = $cparking->extension_no; $i <= $cparking->extension_no + $cparking->no_of_slot; $i++){

            //     Extension::create([
            //         'organization_id' => auth()->user()->organization->id,
            //         'name' => $cparking->name,
            //         'code' => $i,
            //         'extension_type' => '5',
            //         'function_id' => $func->id,
            //         'destination_id' => $cparking->id,
            //     ]);

            // }

            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('call_parkings.call_parking.index')
                ->with('success_message', __('Call Parking was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified call parking.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $callParking = CallParking::findOrFail($id);
        
        // $functions = Function::pluck('id','id')->all();
        // $destinations = Destination::pluck('id','id')->all();
                
        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        
        $functions = Func::getFuncList();
        $destinations = $this->dist_by_function( $callParking->function->func, 0, true );


        if($request->ajax())
            return view('call_parkings.form', compact('callParking','functions','destinations', 'voices'))->with(['action'=>route('call_parkings.call_parking.update',$id),'method'=>'PUT']);
        else
            return view('call_parkings.edit', compact('callParking','functions','destinations', 'voices'));
    }

    /**
     * Update the specified call parking in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
            $data = $this->getData($request, $id);
            $data['record']  = $request->has('record');
            
            $callParking = CallParking::findOrFail($id);
            $callParking->removeExtensions();

            $func = Func::select('id')->where('func', $data['function_id'])->first();
            $data['function_id'] = $func->id;

            $callParking->update($data);
            $callParking->addExtensions();
            
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('call_parkings.call_parking.index')
                ->with('success_message', __('Call Parking was successfully updated.'));
            
    }

    /**
     * Remove the specified call parking from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $callParking = CallParking::findOrFail($id);
            $callParking->removeExtensions();
            $callParking->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('call_parkings.call_parking.index')
                ->with('success_message', __('Call Parking was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified call parking for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $callParking = CallParking::findOrFail($id);  
          
            $callParking->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified call parking for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                $cparkings = CallParking::whereIn('id',$ids)->get();
                
                foreach($cparkings as $cparking){
                    $cparking->removeExtensions();
                }

                CallParking::whereIn('id',$ids)->delete();

            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new CallParking)->getTable(), $field) )
                          CallParking::whereIn('id',$ids)->update([$field=>$val]);
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
    protected function getData(Request $request, $id = 0)
    {


        $rules = [
            'name' => 'required|string|min:1|max:191',
            'extension_no' => ['required', 'numeric', 'min:100', function ($attribute, $value, $fail) use($request) {
                
                $end = intval($request->input('extension_no')) + intval($request->input('no_of_slot'));

                if (Extension::where('organization_id', auth()->user()->organization_id)->where('code', '>=', $request->input('extension_no') )->where('code', '<=', $end)->exists()) {  
                    $fail('The selected slot is not available.');
                }

            }],
            'no_of_slot' => 'required|numeric|min:1|max:2147483647',
            'music_on_hold' => 'required|numeric|min:0|max:2147483647',
            'timeout' => 'required|numeric|min:10|max:2147483647',
            'function_id' => 'required',
            'destination_id' => 'required', 
        ];

        
        if($id > 0){

            $callParking = CallParking::find($id);

            $extIds = Extension::where('organization_id', auth()->user()->organization_id)->where('code', '>=', $callParking->extension_no )->where('code', '<=', $callParking->extension_no + $callParking->no_of_slot)->pluck('id')->toArray();

            $rules['extension_no'] = ['required', 'numeric', 'min:100', function ($attribute, $value, $fail) use($request, $extIds) {

                $end = intval($request->input('extension_no')) + intval($request->input('no_of_slot'));

                if (Extension::where('organization_id', auth()->user()->organization->id)
                    ->where('code', '>=', $request->input('extension_no'))
                    ->where('code', '<=', $end)
                    ->whereNotIn('id', $extIds) // Exclude multiple extension IDs
                    ->exists()) {  
                    $fail('The selected slot is not available.');
                }

            }];
        }

        $data = $request->validate($rules);

        return $data;
    }

}
