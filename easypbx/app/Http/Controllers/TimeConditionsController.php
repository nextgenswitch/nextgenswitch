<?php

namespace App\Http\Controllers;

use Schema;
use Exception;
use App\Models\Func;
use App\Models\Function;
use App\Models\TimeGroup;
use App\Models\Destination;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TimeCondition;
use App\Http\Traits\FuncTrait;
use App\Models\MatchedFunction;
use App\Models\MatchedDestination;
use App\Http\Controllers\Controller;

class TimeConditionsController extends Controller
{

    use FuncTrait;

    /**
     * Display a listing of the time conditions.
     *
     * @return Illuminate\View\View
     */

    public function __construct(){
        config(['menu.group' => 'menu-incoming']);
    }

    public function index(Request $request)
    {
        


        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $timeCondition = TimeCondition::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $timeCondition->where('name', 'LIKE', '%' . $q . '%');
        //dd(config('view.id'));
        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $timeCondition->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $timeCondition->orderBy($sorta[0],$sorta[1]);
        }else
            $timeCondition->orderBy('created_at','DESC'); 
        




        $timeConditions = $timeCondition->paginate($perPage);
        
        $timeConditions->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'timeConditions.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new TimeCondition)->getTable());  
               
                $callback = function() use($timeConditions, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($timeConditions as $timeCondition) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $timeCondition->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('time_conditions.table', compact('timeConditions'));
        } 
        
        return view('time_conditions.index', compact('timeConditions'));


    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }


    /**
     * Show the form for creating a new time condition.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $timeGroups = TimeGroup::where('organization_id', auth()->user()->organization_id)->pluck('name','id');

        $functions    = Func::getFuncList();
        $destinations = [];
        $matched_destinations = [];

        
        if($request->ajax())
            return view('time_conditions.form', compact('timeGroups', 'functions', 'destinations', 'matched_destinations'))->with(['action'=>route('time_conditions.time_condition.store'),'timeCondition' => null,'method'=>'POST']);
        else
            return view('time_conditions.create', compact('timeGroups', 'destinations', 'functions', 'matched_destinations'));
    }

    /**
     * Store a new time condition in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {         
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;

            $function = Func::select( 'id' )->where( 'func', $data['matched_function_id'] )->first();
            $data['matched_function_id']  = $function->id;
            
            $function = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();
            $data['function_id']  = $function->id;

            TimeCondition::create($data);

            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('time_conditions.time_condition.index')
                ->with('success_message', __('Time Condition was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified time condition.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! TimeCondition::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $timeCondition = TimeCondition::findOrFail($id);
        $timeGroups = TimeGroup::pluck('name','id')->all();
        
        $functions       = Func::getFuncList();
        $destinations    = $this->dist_by_function( $timeCondition->func->func, 0, true );
        
        $matched_destinations = $this->dist_by_function( $timeCondition->matchedFunc->func, 0, true );


        if($request->ajax())
            return view('time_conditions.form', compact('timeCondition', 'timeGroups', 'functions', 'destinations', 'matched_destinations'))->with(['action'=>route('time_conditions.time_condition.update',$id),'method'=>'PUT']);
        else
            return view('time_conditions.edit', compact('timeCondition', 'timeGroups', 'functions', 'destinations', 'matched_destinations'));
    }

    /**
     * Update the specified time condition in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
            $data = $this->getData($request);

            if(! TimeCondition::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $timeCondition = TimeCondition::findOrFail($id);
            $function = Func::select( 'id' )->where( 'func', $data['matched_function_id'] )->first();
            $data['matched_function_id']  = $function->id;
            
            $function = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();
            $data['function_id']  = $function->id;
            
            $timeCondition->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('time_conditions.time_condition.index')
                ->with('success_message', __('Time Condition was successfully updated.'));
            
    }

    /**
     * Remove the specified time condition from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! TimeCondition::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $timeCondition = TimeCondition::findOrFail($id);
            $timeCondition->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('time_conditions.time_condition.index')
                ->with('success_message', __('Time Condition was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified time condition for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! TimeCondition::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $timeCondition = TimeCondition::findOrFail($id);  
          
            $timeCondition->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified time condition for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                TimeCondition::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new TimeCondition)->getTable(), $field) )
                          TimeCondition::whereIn('id',$ids)->update([$field=>$val]);
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
            'time_group_id' => 'required',
            'matched_function_id' => 'required',
            'matched_destination_id' => 'required',
            'function_id' => 'required',
            'destination_id' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
