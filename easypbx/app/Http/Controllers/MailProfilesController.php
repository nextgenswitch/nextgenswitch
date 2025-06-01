<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\MailProfile;
use Illuminate\Http\Request;
use Exception;
use Schema;

class MailProfilesController extends Controller
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
        $mailProfile = MailProfile::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $mailProfile->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $mailProfile->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $mailProfile->orderBy($sorta[0],$sorta[1]);
        }else
            $mailProfile->orderBy('created_at','DESC'); 
        




        $mailProfiles = $mailProfile->paginate($perPage);
        
        $mailProfiles->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'mailProfiles.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new MailProfile)->getTable());  
               
                $callback = function() use($mailProfiles, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($mailProfiles as $mailProfile) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $mailProfile->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('mail_profiles.table', compact('mailProfiles'));
        } 
        
        return view('mail_profiles.index', compact('mailProfiles'));


    }

    /**
     * Show the form for creating a new sms profile.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->ajax())
            return view('mail_profiles.form')->with(['action'=>route('mail_profiles.mail_profile.store'),'mailProfile' => null,'method'=>'POST']);
        else
            return view('mail_profiles.create');
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

            MailProfile::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('mail_profiles.mail_profile.index')
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
        if(! MailProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        $mailProfile = MailProfile::findOrFail($id);


        if($request->ajax())
            return view('mail_profiles.form', compact('mailProfile'))->with(['action'=>route('mail_profiles.mail_profile.update',$id),'method'=>'PUT']);
        else
            return view('mail_profiles.edit', compact('mailProfile'));
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
        if(! MailProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
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

            $mailProfile = MailProfile::findOrFail($id);
            $mailProfile->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('mail_profiles.mail_profile.index')
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
            if(! MailProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
            $mailProfile = MailProfile::findOrFail($id);
            $mailProfile->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('mail_profiles.mail_profile.index')
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
            if(! MailProfile::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
            $mailProfile = MailProfile::findOrFail($id);  
          
            $mailProfile->update($request->all());

           
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
                MailProfile::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new MailProfile)->getTable(), $field) )
                          MailProfile::whereIn('id',$ids)->update([$field=>$val]);
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
