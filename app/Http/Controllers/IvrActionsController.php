<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ivr;
use App\Models\Func;
use App\Models\VoiceFile;
use App\Models\Extension;
use App\Models\ExtensionGroup;
use App\Models\CustomFunc;
use App\Models\IvrAction;
use App\Http\Traits\FuncTrait;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;
use Schema;

class IvrActionsController extends Controller
{
    use FuncTrait;

    /**
     * Display a listing of the ivr actions.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request, $ivr)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';

        $orid = auth()->user()->organization_id;
        $ivrAction = IvrAction::with('ivr','func')->where('organization_id', $orid)->where('ivr_id', $ivr);
        $ivrs = Ivr::where('organization_id', $orid)->pluck('name', 'id')->toArray();
        $ivr = Ivr::find($ivr);

        if(!empty($q))  $ivrAction->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $ivrAction->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $ivrAction->orderBy($sorta[0],$sorta[1]);
        }else
            $ivrAction->orderBy('created_at','DESC'); 
        




        $ivrActions = $ivrAction->paginate($perPage);
        
        $ivrActions->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'ivrActions.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new IvrAction)->getTable());  
               
                $callback = function() use($ivrActions, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($ivrActions as $ivrAction) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $ivrAction->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('ivr_actions.table', compact('ivrActions', 'ivrs', 'ivr'));
        } 
        
        return view('ivr_actions.index', compact('ivrActions', 'ivrs', 'ivr'));


    }


    public function destinations($function, $ivr = 0){

        if(request()->ajax()) return $this->dist_by_function($function, $ivr);
        die();
    }




    /**
     * Show the form for creating a new ivr action.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request, $ivr_id)
    {
        $destinations = array();
        $digits = IvrAction::ivr_digits($ivr_id);
        $ivrAction = new IvrAction(['ivr_id' => $ivr_id]);
        $functions = Func::where('organization_id', 0)->pluck('name', 'func')->toArray();


        
        if($request->ajax())
            return view('ivr_actions.form', compact('destinations', 'functions'))->with(['action'=>route('ivr_actions.ivr_action.store'),'ivrAction' => $ivrAction, 'digits' => $digits, 'method'=>'POST']);
        else
            return view('ivr_actions.create', compact('destinations', 'ivrAction', 'digits', 'functions'));
    }

    /**
     * Store a new ivr action in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);

            $data['organization_id'] = auth()->user()->organization_id;


            $func = Func::select('id')->where('func', $data['function_id'])->first();
            $data['function_id'] = $func->id;

            
            $ivr_action = IvrAction::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('ivr_actions.ivr_action.index', $ivr_action->ivr_id)
                ->with('success_message', __('Ivr Action was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified ivr action.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! IvrAction::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $ivrAction = IvrAction::findOrFail($id);
        $digits = IvrAction::ivr_digits($ivrAction->ivr_id, $ivrAction->id);
        $functions = Func::where('organization_id', 0)->pluck('name', 'func')->toArray();

        $destinations = $this->dist_by_function($ivrAction->func->func, $ivrAction->ivr_id, true);


        if($request->ajax())
            return view('ivr_actions.form', compact('ivrAction','destinations', 'functions'))->with(['action'=>route('ivr_actions.ivr_action.update',$id), 'digits' => $digits, 'method'=>'PUT']);
        else
            return view('ivr_actions.edit', compact('ivrAction', 'destinations', 'digits', 'functions'));
    }

    /**
     * Update the specified ivr action in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
            $data = $this->getData($request, $id);

            $func = Func::select('id')->where('func', $data['function_id'])->first();
            $data['function_id'] = $func->id;
            
            if(! IvrAction::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $ivrAction = IvrAction::findOrFail($id);
            $ivrAction = $ivrAction->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('ivr_actions.ivr_action.index', $ivrAction->ivr_id)
                ->with('success_message', __('Ivr Action was successfully updated.'));
            
    }

    /**
     * Remove the specified ivr action from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! IvrAction::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $ivrAction = IvrAction::findOrFail($id);
            $ivrAction->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('ivr_actions.ivr_action.index')
                ->with('success_message', __('Ivr Action was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified ivr action for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            
            if(! IvrAction::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $ivrAction = IvrAction::findOrFail($id);  
          
            $ivrAction->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified ivr action for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                IvrAction::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new IvrAction)->getTable(), $field) )
                          IvrAction::whereIn('id',$ids)->update([$field=>$val]);
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

        $digit_unique_rule = Rule::unique('ivr_actions')->where(function($query) use($request) {
            return $query->where('digit', $request->digit)->where('ivr_id', $request->ivr_id)->where('organization_id', auth()->user()->organization_id);
        });

        if($id > 0 ) $digit_unique_rule->ignore($id);

        $rules = [
            'ivr_id' => 'required',
            'digit' => ['required','numeric','min:0','max:9', $digit_unique_rule],
            'destination_id' => 'required',
            'function_id' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
