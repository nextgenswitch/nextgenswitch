<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Func;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class FuncsController extends Controller
{

    /**
     * Display a listing of the funcs.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $func = Func::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $func->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $func->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $func->orderBy($sorta[0],$sorta[1]);
        }else
            $func->orderBy('created_at','DESC'); 
        




        $funcs = $func->paginate($perPage);
        
        $funcs->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'funcs.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new Func)->getTable());  
               
                $callback = function() use($funcs, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($funcs as $func) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $func->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('funcs.table', compact('funcs'));
        } 
        
        return view('funcs.index', compact('funcs'));


    }

    /**
     * Show the form for creating a new func.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {

        
        if($request->ajax())
            return view('funcs.form')->with(['action'=>route('funcs.func.store'),'func' => null,'method'=>'POST']);
        else
            return view('funcs.create');
    }

    /**
     * Store a new func in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            
            Func::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('funcs.func.index')
                ->with('success_message', __('Func was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified func.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! Func::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $func = Func::findOrFail($id);


        if($request->ajax())
            return view('funcs.form', compact('func'))->with(['action'=>route('funcs.func.update',$id),'method'=>'PUT']);
        else
            return view('funcs.edit', compact('func'));
    }

    /**
     * Update the specified func in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            if(! Func::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $func = Func::findOrFail($id);
            $func->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('funcs.func.index')
                ->with('success_message', __('Func was successfully updated.'));
            
    }

    /**
     * Remove the specified func from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            
            if(! Func::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $func = Func::findOrFail($id);
            $func->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('funcs.func.index')
                ->with('success_message', __('Func was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified func for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! Func::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $func = Func::findOrFail($id);  
          
            $func->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified func for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Func::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Func)->getTable(), $field) )
                          Func::whereIn('id',$ids)->update([$field=>$val]);
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
                'func' => 'required|string|min:1|max:255',
            'func_type' => 'required|string|min:1',
            'name' => 'required|string|min:1|max:255',

        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
