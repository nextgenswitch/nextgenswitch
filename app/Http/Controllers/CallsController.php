<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Organization;
use App\Models\SipUser;
use Illuminate\Http\Request;
use Exception;
use Schema;

class CallsController extends Controller
{

    /**
     * Display a listing of the calls.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $call = Call::with('organization','sipuser')->query();
        if(!empty($q))  $call->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $call->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $call->orderBy($sorta[0],$sorta[1]);
        }else
            $call->orderBy('created_at','DESC'); 
        




        $calls = $call->paginate($perPage);
        
        $calls->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'calls.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new Call)->getTable());  
               
                $callback = function() use($calls, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($calls as $call) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $call->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('calls.table', compact('calls'));
        } 
        
        return view('calls.index', compact('calls'));


    }

    /**
     * Show the form for creating a new call.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $organizations = Organization::pluck('name','id')->all();
$sipUsers = SipUser::pluck('id','id')->all();
        
        if($request->ajax())
            return view('calls.form', compact('organizations','sipUsers'))->with(['action'=>route('calls.call.store'),'call' => null,'method'=>'POST']);
        else
            return view('calls.create', compact('organizations','sipUsers'));
    }

    /**
     * Store a new call in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            
            Call::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('calls.call.index')
                ->with('success_message', __('Call was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified call.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! Call::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $call = Call::findOrFail($id);
        $organizations = Organization::pluck('name','id')->all();
$sipUsers = SipUser::pluck('id','id')->all();

        if($request->ajax())
            return view('calls.form', compact('call','organizations','sipUsers'))->with(['action'=>route('calls.call.update',$id),'method'=>'PUT']);
        else
            return view('calls.edit', compact('call','organizations','sipUsers'));
    }

    /**
     * Update the specified call in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            if(! Call::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $call = Call::findOrFail($id);
            $call->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('calls.call.index')
                ->with('success_message', __('Call was successfully updated.'));
            
    }

    /**
     * Remove the specified call from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            
            if(! Call::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $call = Call::findOrFail($id);
            $call->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('calls.call.index')
                ->with('success_message', __('Call was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified call for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! Call::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $call = Call::findOrFail($id);  
          
            $call->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified call for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Call::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Call)->getTable(), $field) )
                          Call::whereIn('id',$ids)->update([$field=>$val]);
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
                'organization_id' => 'required',
            'channel' => 'required|string|min:1|max:255',
            'sip_user_id' => 'required',
            'call_status' => 'required|string|min:1',
            'connect_time' => 'required|date_format:j/n/Y g:i A',
            'ringing_time' => 'required|date_format:j/n/Y g:i A',
            'establish_time' => 'required|date_format:j/n/Y g:i A',
            'disconnect_time' => 'required|date_format:j/n/Y g:i A',
            'duration' => 'required|numeric|min:-2147483648|max:2147483647',
            'user_agent' => 'required|numeric|min:-2147483648|max:2147483647',
            'uas' => 'boolean', 
        ];
        
        $data = $request->validate($rules);

        $data['uas'] = $request->has('uas');

        return $data;
    }

}
