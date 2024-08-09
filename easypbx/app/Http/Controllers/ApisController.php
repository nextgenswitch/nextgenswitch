<?php

namespace App\Http\Controllers;

use Schema;
use Exception;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\ApiAccessLog;
use Illuminate\Support\Facades\Hash;

class ApisController extends Controller
{

    /**
     * Display a listing of the apis.
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

        $api = ApiKey::where('organization_id', auth()->user()->organization_id);

        if(!empty($q))  $api->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $api->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $api->orderBy($sorta[0],$sorta[1]);
        }else
            $api->orderBy('created_at','DESC'); 
        




        $apis = $api->paginate($perPage);
        
        $apis->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'apis.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new ApiKey)->getTable());  
               
                $callback = function() use($apis, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($apis as $api) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $api->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('apis.table', compact('apis'));
        } 
        
        return view('apis.index', compact('apis'));


    }


    public function logs(Request $request, $id){
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';

        $logs = ApiAccessLog::where('organization_id', auth()->user()->organization_id)->where('api_key_id', $id);

        if(!empty($q))  $logs->where('ip_address', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $logs->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $logs->orderBy($sorta[0],$sorta[1]);
        }else
            $logs->orderBy('created_at','DESC'); 
        

        $logs = $logs->paginate($perPage);
        
        $logs->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if($request->ajax()){
            return view('apis.logs.table', compact('logs'));
        } 
    
        return view('apis.logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new api.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        
        if($request->ajax())
            return view('apis.form')->with(['action'=>route('apis.api.store'),'api' => null,'method'=>'POST']);
        else
            return view('apis.create');
    }


    /**
     * Store a new api in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
            $data = $this->getData($request);
            $data['status'] = $request->has('status') ? $request->input('status') : 0;
            $data['organization_id'] = auth()->user()->organization_id;

            $data['key'] = ApiKey::generateKey();  //Str::random(32);
            $secret = ApiKey::generateSecret();
            $data['secret'] = Hash::make($secret); //Str::random(32);
            $api = ApiKey::create($data);
            if($request->ajax())  return response()->json(['success'=>true,'secret'=>$secret]);

            return view('apis.show', compact('api'));
            //return redirect()->route('apis.api.show', $api->id)
              //  ->with('success_message', __('Api was successfully added.'));
      
    }

    


    public function regenrate($id){
        $api = ApiKey::find($id);
        
        $secret = ApiKey::generateSecret();
        $secret = Hash::make($secret); //Str::random(32);

        $api->secret = $secret;
        $api->update();
        
        return view('apis.show', compact('api'));
    }

    /**
     * Show the form for editing the specified api.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $api = ApiKey::findOrFail($id);
        
        if(! ApiKey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        if($request->ajax())
            return view('apis.form', compact('api'))->with(['action'=>route('apis.api.update',$id),'method'=>'PUT']);
        else
            return view('apis.edit', compact('api'));
    }

    /**
     * Update the specified api in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
            $data = $this->getData($request, $id);
            
            $data['status'] = $request->has('status') ? $request->input('status') : 0;
            $api = ApiKey::findOrFail($id);

            if(! ApiKey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $api->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('apis.api.index')
                ->with('success_message', __('Api was successfully updated.'));
            
    }

    /**
     * Remove the specified api from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! ApiKey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $api = ApiKey::findOrFail($id);
            $api->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('apis.api.index')
                ->with('success_message', __('Api was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified api for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! ApiKey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
            $api = ApiKey::findOrFail($id);  
          
            $api->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified api for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                ApiKey::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Api)->getTable(), $field) )
                          ApiKey::whereIn('id',$ids)->update([$field=>$val]);
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
        $title_unique_rule = Rule::unique( 'api_keys' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'title', $request->title )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if( $id > 0){
            $title_unique_rule->ignore($id);
        }
        
        $rules = [
                'title' => ['required', 'string', 'min:4', 'max:191', $title_unique_rule],
                'status' => 'nullable|numeric|min:1', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
