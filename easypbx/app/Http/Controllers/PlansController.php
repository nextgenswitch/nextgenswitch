<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Exception;
use Schema;

class PlansController extends Controller
{

    public function __construct(){
        config(['menu.group' => 'menu-multi-tenant']);  
    } 

    /**
     * Display a listing of the plans.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $plan = Plan::query();
        if(!empty($q))  $plan->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $plan->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $plan->orderBy($sorta[0],$sorta[1]);
        }else
            $plan->orderBy('created_at','DESC'); 
        




        $plans = $plan->paginate($perPage);
        
        $plans->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'plans.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                $columns = ['name','duration','price']; // specify columns if need
                // $columns = Schema::getColumnListing((new Plan)->getTable());  
               
                $callback = function() use($plans, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($plans as $plan) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $plan->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('plans.table', compact('plans'));
        } 
        
        return view('plans.index', compact('plans'));


    }

    /**
     * Show the form for creating a new plan.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        
        if($request->ajax())
            return view('plans.form')->with(['action'=>route('plans.plan.store'),'plan' => null,'method'=>'POST']);
        else
            return view('plans.create');
    }

    /**
     * Store a new plan in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            
            Plan::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('plans.plan.index')
                ->with('success_message', __('Plan was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified plan.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $plan = Plan::findOrFail($id);
        

        if($request->ajax())
            return view('plans.form', compact('plan'))->with(['action'=>route('plans.plan.update',$id),'method'=>'PUT']);
        else
            return view('plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            $plan = Plan::findOrFail($id);
            $plan->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('plans.plan.index')
                ->with('success_message', __('Plan was successfully updated.'));
            
    }

    /**
     * Remove the specified plan from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('plans.plan.index')
                ->with('success_message', __('Plan was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified plan for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $plan = Plan::findOrFail($id);  
          
            $plan->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified plan for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Plan::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Plan)->getTable(), $field) )
                          Plan::whereIn('id',$ids)->update([$field=>$val]);
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
            'duration' => 'required|numeric|min:-2147483648|max:2147483647',
            'price' => 'required|numeric|min:-999.99999|max:999.99999',
            // 'credit' => 'required|numeric|min:-999.99999|max:999.99999', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
