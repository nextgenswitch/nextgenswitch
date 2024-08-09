<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallLeg;
use App\Models\SipUser;
use Illuminate\Http\Request;
use Exception;
use Schema;

class CallLegsController extends Controller
{

    /**
     * Display a listing of the call legs.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $callLeg = CallLeg::with('call','sipuser')->query();
        if(!empty($q))  $callLeg->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $callLeg->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $callLeg->orderBy($sorta[0],$sorta[1]);
        }else
            $callLeg->orderBy('created_at','DESC'); 
        




        $callLegs = $callLeg->paginate($perPage);
        
        $callLegs->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'callLegs.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new CallLeg)->getTable());  
               
                $callback = function() use($callLegs, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($callLegs as $callLeg) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $callLeg->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('call_legs.table', compact('callLegs'));
        } 
        
        return view('call_legs.index', compact('callLegs'));


    }

    /**
     * Show the form for creating a new call leg.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $calls = Call::pluck('channel','id')->all();
$sipUsers = SipUser::pluck('id','id')->all();
        
        if($request->ajax())
            return view('call_legs.form', compact('calls','sipUsers'))->with(['action'=>route('call_legs.call_leg.store'),'callLeg' => null,'method'=>'POST']);
        else
            return view('call_legs.create', compact('calls','sipUsers'));
    }

    /**
     * Store a new call leg in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            
            CallLeg::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('call_legs.call_leg.index')
                ->with('success_message', __('Call Leg was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified call leg.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! CallLeg::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $callLeg = CallLeg::findOrFail($id);
        $calls = Call::pluck('channel','id')->all();
        $sipUsers = SipUser::pluck('id','id')->all();

        if($request->ajax())
            return view('call_legs.form', compact('callLeg','calls','sipUsers'))->with(['action'=>route('call_legs.call_leg.update',$id),'method'=>'PUT']);
        else
            return view('call_legs.edit', compact('callLeg','calls','sipUsers'));
    }

    /**
     * Update the specified call leg in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            if(! CallLeg::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            $callLeg = CallLeg::findOrFail($id);
            $callLeg->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('call_legs.call_leg.index')
                ->with('success_message', __('Call Leg was successfully updated.'));
            
    }

    /**
     * Remove the specified call leg from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! CallLeg::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $callLeg = CallLeg::findOrFail($id);
            $callLeg->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('call_legs.call_leg.index')
                ->with('success_message', __('Call Leg was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified call leg for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! CallLeg::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            $callLeg = CallLeg::findOrFail($id);  
          
            $callLeg->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified call leg for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                CallLeg::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new CallLeg)->getTable(), $field) )
                          CallLeg::whereIn('id',$ids)->update([$field=>$val]);
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
                'call_id' => 'required',
            'channel' => 'required|string|min:1|max:255',
            'sip_user_id' => 'required',
            'call_status' => 'required|string|min:1',
            'connect_time' => 'required|date_format:j/n/Y g:i A',
            'ringing_time' => 'required|date_format:j/n/Y g:i A',
            'establish_time' => 'required|date_format:j/n/Y g:i A',
            'disconnect_time' => 'required|date_format:j/n/Y g:i A',
            'duration' => 'required|numeric|min:-2147483648|max:2147483647', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
