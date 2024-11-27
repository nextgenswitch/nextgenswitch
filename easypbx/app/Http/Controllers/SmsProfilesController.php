<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SmsProfile;
use Illuminate\Http\Request;
use Exception;
use Schema;

class SmsProfilesController extends Controller
{

    /**
     * Display a listing of the sms profiles.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $smsProfile = SmsProfile::where( 'organization_id', auth()->user()->organization_id );
        if(!empty($q))  $smsProfile->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $smsProfile->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $smsProfile->orderBy($sorta[0],$sorta[1]);
        }else
            $smsProfile->orderBy('created_at','DESC'); 
        




        $smsProfiles = $smsProfile->paginate($perPage);
        
        $smsProfiles->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'smsProfiles.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new SmsProfile)->getTable());  
               
                $callback = function() use($smsProfiles, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($smsProfiles as $smsProfile) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $smsProfile->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('sms_profiles.table', compact('smsProfiles'));
        } 
        
        return view('sms_profiles.index', compact('smsProfiles'));


    }

    /**
     * Show the form for creating a new sms profile.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->ajax())
            return view('sms_profiles.form')->with(['action'=>route('sms_profiles.sms_profile.store'),'smsProfile' => null,'method'=>'POST']);
        else
            return view('sms_profiles.create');
    }

    /**
     * Store a new sms profile in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);     
            $data['organization_id'] = auth()->user()->organization_id;

            $data['status'] = isset($data['status']) ? 1 : 0;
            $data['default'] = isset($data['default']) ? 1 : 0;


            $options = $request->input('options');
            $ops = array();

            if(is_array($options) && count($options)){
                
                foreach($options['name'] as $key => $name){
                    if(isset($options['value'][$key])){
                        $ops[$name] = $options['value'][$key];
                    }
                }
            }
            $data['options'] = json_encode($ops);

            SmsProfile::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('sms_profiles.sms_profile.index')
                ->with('success_message', __('Sms Profile was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified sms profile.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! SmsProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        $smsProfile = SmsProfile::findOrFail($id);


        if($request->ajax())
            return view('sms_profiles.form', compact('smsProfile'))->with(['action'=>route('sms_profiles.sms_profile.update',$id),'method'=>'PUT']);
        else
            return view('sms_profiles.edit', compact('smsProfile'));
    }

    /**
     * Update the specified sms profile in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        if(! SmsProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $data = $this->getData($request);
            
            $data['status'] = isset($data['status']) ? 1 : 0;
            $data['default'] = isset($data['default']) ? 1 : 0;


            $options = $request->input('options');
            $ops = array();

            if(is_array($options) && count($options)){
                
                foreach($options['name'] as $key => $name){
                    if(isset($options['value'][$key])){
                        $ops[$name] = $options['value'][$key];
                    }
                }
            }
            $data['options'] = json_encode($ops);

            $smsProfile = SmsProfile::findOrFail($id);
            $smsProfile->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('sms_profiles.sms_profile.index')
                ->with('success_message', __('Sms Profile was successfully updated.'));
            
    }

    /**
     * Remove the specified sms profile from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! SmsProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
            $smsProfile = SmsProfile::findOrFail($id);
            $smsProfile->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('sms_profiles.sms_profile.index')
                ->with('success_message', __('Sms Profile was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified sms profile for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! SmsProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
            $smsProfile = SmsProfile::findOrFail($id);  
          
            $smsProfile->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified sms profile for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                SmsProfile::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new SmsProfile)->getTable(), $field) )
                          SmsProfile::whereIn('id',$ids)->update([$field=>$val]);
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
                'default' => 'nullable|string|min:1',
            'name' => 'required|string|min:1|max:191',
            'provider' => 'required|string|min:1|max:191',
            'status' => 'nullable|string|min:1', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
