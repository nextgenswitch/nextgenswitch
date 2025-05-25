<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class LeadsController extends Controller
{

    public function __construct(){
        config(['menu.group' => 'menu-crm']);  
    } 

    /**
     * Display a listing of the leads.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $lead = Lead::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $lead->where('name', 'LIKE', '%' . $q . '%')
            ->orWhere('phone', 'LIKE', '%' . $q . '%')
            ->orWhere('email', 'LIKE', '%' . $q . '%')
            ->orWhere('company', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $lead->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $lead->orderBy($sorta[0],$sorta[1]);
        }else
            $lead->orderBy('created_at','DESC'); 
        




        $leads = $lead->paginate($perPage);
        
        $leads->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'leads.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                $columns = ['name','designation','phone', 'email', 'website', 'company', 'address', 'source', 'status', 'notes']; // specify columns if need
                // $columns = Schema::getColumnListing((new Lead)->getTable());  
               
                $callback = function() use($leads, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($leads as $lead) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column){
                            
                            $row[$column] = $lead->{$column};
                            
                        }
                             

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        $statuses = Lead::where('organization_id', auth()->user()->organization_id )->pluck('status')->toArray();
        $statuses = array_unique(array_merge(config('enums.lead_status'), $statuses));
        

        if($request->ajax()){
            return view('leads.table', compact('leads', 'statuses'));
        } 
        
        return view('leads.index', compact('leads', 'statuses'));


    }

    /**
     * Show the form for creating a new lead.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $statuses = Lead::where('organization_id', auth()->user()->organization_id )->pluck('status')->toArray();
        $sources = Lead::where('organization_id', auth()->user()->organization_id )->pluck('source')->toArray();
        
        $statuses = array_unique(array_merge(config('enums.lead_status'), $statuses));
        $sources = array_unique(array_merge(config('enums.lead_source'), $sources));


        if($request->ajax())
            return view('leads.form', compact('statuses', 'sources'))->with(['action'=>route('leads.lead.store'),'lead' => null,'method'=>'POST']);
        else
            return view('leads.create', compact('statuses', 'sources'));
    }

    /**
     * Store a new lead in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            
            Lead::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('leads.lead.index')
                ->with('success_message', __('Lead was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified lead.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! Lead::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $lead = Lead::findOrFail($id);
        $statuses = Lead::where('organization_id', auth()->user()->organization_id )->pluck('status')->toArray();
        $sources = Lead::where('organization_id', auth()->user()->organization_id )->pluck('source')->toArray();
        
        $statuses = array_unique(array_merge(config('enums.lead_status'), $statuses));
        $sources = array_unique(array_merge(config('enums.lead_source'), $sources));

        if($request->ajax())
            return view('leads.form', compact('lead','statuses', 'sources'))->with(['action'=>route('leads.lead.update',$id),'method'=>'PUT']);
        else
            return view('leads.edit', compact('lead','statuses', 'sources'));
    }

    /**
     * Update the specified lead in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);

            if(! Lead::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $lead = Lead::findOrFail($id);
            $lead->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('leads.lead.index')
                ->with('success_message', __('Lead was successfully updated.'));
            
    }

    /**
     * Remove the specified lead from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! Lead::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $lead = Lead::findOrFail($id);
            $lead->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('leads.lead.index')
                ->with('success_message', __('Lead was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified lead for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            
            if(! Lead::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $lead = Lead::findOrFail($id);  
          
            $lead->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified lead for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Lead::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Lead)->getTable(), $field) )
                          Lead::whereIn('id',$ids)->update([$field=>$val]);
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
                // 'organization_id' => 'required',
            'name' => 'required|string|min:1|max:191',
            'designation' => 'nullable|string|min:0|max:191',
            'phone' => 'required|string|min:1|max:191',
            'email' => 'nullable|string|min:0|max:191',
            'website' => 'nullable|string|min:0|max:191',
            'company' => 'nullable|string|min:0|max:191',
            'address' => 'nullable',
            'source' => 'nullable|string|min:0|max:191',
            'notes' => 'nullable',
            'status' => 'required|string|min:0', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
