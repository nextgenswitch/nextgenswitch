<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Extension;
use App\Models\ExtensionGroup;
use App\Models\Func;
use App\Models\Ivr;
use App\Models\CustomFunc;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Exception;
use Schema;

class ApplicationsController extends Controller
{

    use FuncTrait;
    /**
     * Display a listing of the applications.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-application']);  
    } 
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $application = Extension::with('func')->where('organization_id', auth()->user()->organization_id)->where('extension_type', '3');


        if(!empty($q))  $application->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $application->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $application->orderBy($sorta[0],$sorta[1]);
        }else
            $application->orderBy('created_at','DESC'); 
        




        $applications = $application->paginate($perPage);
        
        $applications->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'applications.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new Extension)->getTable());
               
                $callback = function() use($applications, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($applications as $application) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $application->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('applications.table', compact('applications'));
        } 
        
        return view('applications.index', compact('applications'));


    }


    public function destinations($function){

        if(request()->ajax()) return $this->dist_by_function($function);
        die();


        // if(request()->ajax()){
        //     $data = array();
        //     $orid = auth()->user()->organization_id;



        //     if($function == 'extension')
        //         $data = Extension::where('organization_id', $orid)->where('extension_type', '1')->pluck('name', 'id')->toArray();

        //     else if($function == 'groupcall')
        //         $data = ExtensionGroup::where('organization_id', $orid)->pluck('name', 'id')->toArray();

        //     else if($function == 'ivr')
        //         $data = Ivr::where('organization_id', $orid)->pluck('name', 'id')->toArray();

        //     else if($function == 'custom_function')
        //         $data = CustomFunc::where('organization_id', $orid)->pluck('name', 'id')->toArray();


        //     $html = '<option> Select destination </option>';

        //     if(count($data) > 0){
        //         foreach ($data as $key => $value) {
        //             $html .= "<option value='$key'> $value  </option>";
        //         }
        //     }

        //     echo $html;
        //     die();
        // }
    }


    /**
     * Show the form for creating a new application.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $functions = Func::getFuncList();
        

        $destinations = array();

        if($request->ajax())
            return view('applications.form', compact('destinations', 'functions'))->with(['action'=>route('applications.application.store'),'application' => null,'method'=>'POST']);
        else
            return view('applications.create', compact('destinations', 'functions'));
    }

    /**
     * Store a new application in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            
            $data = $this->getData($request);

            $data['organization_id'] = auth()->user()->organization_id;
            $data['extension_type'] = '3';


            $func = Func::select('id')->where('func', $data['function_id'])->first();
            $data['function_id'] = $func->id;

            Extension::create($data);


            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('applications.application.index')
                ->with('success_message', __('Application was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified application.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $application = Extension::with('func')->findOrFail($id);
        $functions = Func::getFuncList();

        $destinations = array();
        $orid = auth()->user()->organization_id;

        // if($application->func->func == 'extension')
        //     $destinations = Extension::where('organization_id', $orid)->where('extension_type', '1')->pluck('name', 'id')->toArray();

        // else if($application->func->func == 'groupcall')
        //     $destinations = ExtensionGroup::where('organization_id', $orid)->pluck('name', 'id')->toArray();

        // else if($application->func->func == 'ivr')
        //     $destinations = Ivr::where('organization_id', $orid)->pluck('name', 'id')->toArray();

        // else if($application->func->func == 'custom_function')
        //     $destinations = CustomFunc::where('organization_id', $orid)->pluck('name', 'id')->toArray();


        $destinations = $this->dist_by_function($application->func->func, 0, true);

        if($request->ajax())
            return view('applications.form', compact('application','destinations', 'functions'))->with(['action'=>route('applications.application.update',$id),'method'=>'PUT']);
        else
            return view('applications.edit', compact('application','destinations', 'functions'));
    }

    /**
     * Update the specified application in the storage.
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
            
            if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $application = Extension::findOrFail($id);

            $application->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('applications.application.index')
                ->with('success_message', __('Application was successfully updated.'));
            
    }

    /**
     * Remove the specified application from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $application = Extension::findOrFail($id);
            $application->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('applications.application.index')
                ->with('success_message', __('Application was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified application for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $application = Extension::findOrFail($id);
          
            $application->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified application for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Extension::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Extension)->getTable(), $field) )
                          Extension::whereIn('id',$ids)->update([$field=>$val]);
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


        $code_unique_rule = Rule::unique('extensions')->where(function($query) use($request) {
                return $query->where('code', $request->code)->where('organization_id', auth()->user()->organization_id);
            });

        if($id > 0) $code_unique_rule->ignore($id);

        $rules = [
            'code' => ['required', 'numeric', 'min:1000', 'max:2147483647', $code_unique_rule],
            'destination_id' => 'required',
            'function_id' => 'required',
            'name' => 'required|string|min:1|max:255',
            'status' => 'nullable|min:1'
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
