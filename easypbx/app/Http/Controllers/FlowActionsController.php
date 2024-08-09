<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowAction;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class FlowActionsController extends Controller
{

    /**
     * Display a listing of the flow actions.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $flowAction = FlowAction::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $flowAction->where('title', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $flowAction->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $flowAction->orderBy($sorta[0],$sorta[1]);
        }else
            $flowAction->orderBy('created_at','DESC'); 
        




        $flowActions = $flowAction->paginate($perPage);
        
        $flowActions->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'flowActions.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new FlowAction)->getTable());  
               
                $callback = function() use($flowActions, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($flowActions as $flowAction) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $flowAction->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('flow_actions.table', compact('flowActions'));
        } 
        
        return view('flow_actions.index', compact('flowActions'));


    }

    /**
     * Show the form for creating a new flow action.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $flows = Flow::where('organization_id', auth()->user()->organization_id)->pluck('title', 'id');
        if($request->ajax())
            return view('flow_actions.form')->with(['action'=>route('flow_actions.flow_action.store'),'flowAction' => null,'method'=>'POST']);
        else
            return view('flow_actions.create');
    }

    /**
     * Store a new flow action in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            FlowAction::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('flow_actions.flow_action.index')
                ->with('success_message', __('Flow Action was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified flow action.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $flowAction = FlowAction::findOrFail($id);
        $organizations = Organization::pluck('name','id')->all();

        if($request->ajax())
            return view('flow_actions.form', compact('flowAction','organizations'))->with(['action'=>route('flow_actions.flow_action.update',$id),'method'=>'PUT']);
        else
            return view('flow_actions.edit', compact('flowAction','organizations'));
    }

    /**
     * Update the specified flow action in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            $flowAction = FlowAction::findOrFail($id);
            $flowAction->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('flow_actions.flow_action.index')
                ->with('success_message', __('Flow Action was successfully updated.'));
            
    }

    /**
     * Remove the specified flow action from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $flowAction = FlowAction::findOrFail($id);
            $flowAction->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('flow_actions.flow_action.index')
                ->with('success_message', __('Flow Action was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified flow action for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $flowAction = FlowAction::findOrFail($id);  
          
            $flowAction->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified flow action for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                FlowAction::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new FlowAction)->getTable(), $field) )
                          FlowAction::whereIn('id',$ids)->update([$field=>$val]);
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
                'action_type' => 'required|numeric|min:0',
            'action_value' => 'required|string|min:1|max:191',
            'title' => 'required|string', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
