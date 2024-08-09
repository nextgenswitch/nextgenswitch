<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class CustomFormsController extends Controller
{
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 

    /**
     * Display a listing of the custom forms.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $customForm = CustomForm::where('organization_id', auth()->user()->organization_id);
        
        if(!empty($q))  $customForm->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $customForm->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $customForm->orderBy($sorta[0],$sorta[1]);
        }else
            $customForm->orderBy('created_at','DESC'); 
        




        $customForms = $customForm->paginate($perPage);
        
        $customForms->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'customForms.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new CustomForm)->getTable());  
               
                $callback = function() use($customForms, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($customForms as $customForm) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $customForm->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('custom_forms.table', compact('customForms'));
        } 
        
        return view('custom_forms.index', compact('customForms'));


    }

    /**
     * Show the form for creating a new custom form.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        
        if($request->ajax())
            return view('custom_forms.form')->with(['action'=>route('custom_forms.custom_form.store'),'customForm' => null,'method'=>'POST']);
        else
            return view('custom_forms.create');
    }

    /**
     * Store a new custom form in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;

            CustomForm::create($data);
            
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('custom_forms.custom_form.index')
                ->with('success_message', __('Custom Form was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified custom form.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $customForm = CustomForm::findOrFail($id);
        $organizations = Organization::pluck('name','id')->all();

        if($request->ajax())
            return view('custom_forms.form', compact('customForm','organizations'))->with(['action'=>route('custom_forms.custom_form.update',$id),'method'=>'PUT']);
        else
            return view('custom_forms.edit', compact('customForm','organizations'));
    }

    /**
     * Update the specified custom form in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            $customForm = CustomForm::findOrFail($id);
            $customForm->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('custom_forms.custom_form.index')
                ->with('success_message', __('Custom Form was successfully updated.'));
            
    }

    /**
     * Remove the specified custom form from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $customForm = CustomForm::findOrFail($id);
            $customForm->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('custom_forms.custom_form.index')
                ->with('success_message', __('Custom Form was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified custom form for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $customForm = CustomForm::findOrFail($id);  
          
            $customForm->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified custom form for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                CustomForm::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new CustomForm)->getTable(), $field) )
                          CustomForm::whereIn('id',$ids)->update([$field=>$val]);
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
                'fields' => 'required|string',
            'name' => 'required|string|min:1|max:191',
            
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
