<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Exception;
use Schema;

class ContactGroupsController extends Controller
{

    /**
     * Display a listing of the contact groups.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 
    
    public function index(Request $request)
    {
       
        
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $contactGroup = ContactGroup::where('organization_id','=', auth()->user()->organization_id);
        if(!empty($q))  $contactGroup->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $contactGroup->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $contactGroup->orderBy($sorta[0],$sorta[1]);
        }else
            $contactGroup->orderBy('created_at','DESC'); 




        $contactGroups = $contactGroup->paginate($perPage);
        
        $contactGroups->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'contactGroups.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                $columns = ['organization_id','name']; // specify columns if need

               
                $callback = function() use($contactGroups, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($contactGroups as $contactGroup) {

                        foreach($columns as $column)
                             $row[$column] = $contactGroup->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                
        

        if($request->ajax()){
            return view('contact_groups.table', compact('contactGroups'));
        } 
        
        return view('contact_groups.index', compact('contactGroups'));


    }

    /**
     * Show the form for creating a new contact group.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $users = User::pluck('name','id')->all();
        
        if($request->ajax())
            return view('contact_groups.form', compact('users'))->with(['action'=>route('contact_groups.contact_group.store'),'contactGroup' => null,'method'=>'POST']);
        else
            return view('contact_groups.create', compact('users'));
    }

    /**
     * Store a new contact group in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        if($request->ajax()){
            
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            
            ContactGroup::create($data);
            
            return response()->json(['success'=>true]);
        }
        try {
            
            $data = $this->getData($request);
            
            ContactGroup::create($data);

            return redirect()->route('contact_groups.contact_group.index')
                ->with('success_message', 'Contact Group was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Show the form for editing the specified contact group.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! ContactGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $contactGroup = ContactGroup::findOrFail($id);
        $users = User::pluck('name','id')->all();

        if($request->ajax())
            return view('contact_groups.form', compact('contactGroup','users'))->with(['action'=>route('contact_groups.contact_group.update',$id),'method'=>'PUT']);
        else
            return view('contact_groups.edit', compact('contactGroup','users'));
    }

    /**
     * Update the specified contact group in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        if($request->ajax()){

            
            $data = $this->getData($request, $id);

            if(! ContactGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            
            $contactGroup = ContactGroup::findOrFail($id);
            $contactGroup->update($data);
            
            return response()->json(['success'=>true]);
        }

        try {
            
            $data = $this->getData($request);
            if(! ContactGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $contactGroup = ContactGroup::findOrFail($id);
            $contactGroup->update($data);

            return redirect()->route('contact_groups.contact_group.index')
                ->with('success_message', 'Contact Group was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified contact group from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! ContactGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $contactGroup = ContactGroup::findOrFail($id);
            $contactGroup->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('contact_groups.contact_group.index')
                ->with('success_message', 'Contact Group was successfully deleted.');
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * update the specified contact group for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! ContactGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $contactGroup = ContactGroup::findOrFail($id);  
          
            $contactGroup->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified contact group for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                ContactGroup::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new ContactGroup)->getTable(), $field) )
                          ContactGroup::whereIn('id',$ids)->update([$field=>$val]);
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
        $unique = Rule::unique('contact_groups')->where(function ($query) use ($request) {
            return $query->where('organization_id',  auth()->user()->organization_id );
        });

        $rules = [
            'name' => ['required', 'string', 'max:100', $unique]
        ];
        

        if($id > 0){
            $unique->ignore($id);
        }
        

        $data = $request->validate($rules);

        return $data;
    }

}
