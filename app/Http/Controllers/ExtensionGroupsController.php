<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Models\ExtensionGroup;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class ExtensionGroupsController extends Controller
{

    /**
     * Display a listing of the extension groups.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $extensionGroup = ExtensionGroup::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $extensionGroup->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $extensionGroup->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $extensionGroup->orderBy($sorta[0],$sorta[1]);
        }else
            $extensionGroup->orderBy('created_at','DESC'); 
        



        $extensions = Extension::where( 'organization_id', auth()->user()->organization_id )->pluck('name','id')->all();

        $extensionGroups = $extensionGroup->paginate($perPage);
        
        $extensionGroups->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'extensionGroups.csv';
         
                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new ExtensionGroup)->getTable());  
               

                $callback = function() use($extensionGroups, $columns, $extensions) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($extensionGroups as $etgGroup) {


                        foreach($columns as $column){


                            if($column == 'extension_id'){
                                $ids = [];

                                foreach($etgGroup->extension_id as $eid){
                                    if(isset($extensions[$eid]))
                                        $eg[] = $extensions[$eid];

                                }





                                $row[$column] = implode(",",$eg);


                            }else
                                $row[$column] = $etgGroup->{$column};

                        }

                        fputcsv($file, $row);
                    }


                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('extension_groups.table', compact('extensionGroups', 'extensions'));
        } 
        
        return view('extension_groups.index', compact('extensionGroups', 'extensions'));


    }

    /**
     * Show the form for creating a new extension group.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {

$extensions = Extension::pluck('name','id')->all();
        
        if($request->ajax())
            return view('extension_groups.form', compact('extensions'))->with(['action'=>route('extension_groups.extension_group.store'),'extensionGroup' => null,'method'=>'POST']);
        else
            return view('extension_groups.create', compact('extensions'));
    }

    /**
     * Store a new extension group in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;

// return $data;

            ExtensionGroup::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('extension_groups.extension_group.index')
                ->with('success_message', __('Extension Group was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified extension group.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! ExtensionGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $extensionGroup = ExtensionGroup::findOrFail($id);
        $extensions = Extension::pluck('name','id')->all();

        if($request->ajax())
            return view('extension_groups.form', compact('extensionGroup','extensions'))->with(['action'=>route('extension_groups.extension_group.update',$id),'method'=>'PUT']);
        else
            return view('extension_groups.edit', compact('extensionGroup','extensions'));
    }

    /**
     * Update the specified extension group in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            if(! ExtensionGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            $extensionGroup = ExtensionGroup::findOrFail($id);
            $extensionGroup->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('extension_groups.extension_group.index')
                ->with('success_message', __('Extension Group was successfully updated.'));
            
    }

    /**
     * Remove the specified extension group from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! ExtensionGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            $extensionGroup = ExtensionGroup::findOrFail($id);
            $extensionGroup->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('extension_groups.extension_group.index')
                ->with('success_message', __('Extension Group was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified extension group for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            if(! ExtensionGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            $extensionGroup = ExtensionGroup::findOrFail($id);  
          
            $extensionGroup->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified extension group for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                ExtensionGroup::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new ExtensionGroup)->getTable(), $field) )
                          ExtensionGroup::whereIn('id',$ids)->update([$field=>$val]);
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
            'name' => 'required|string|min:1|max:255',
            'extension_id' => 'required',
            'algorithm' => 'required|string|min:1|max:255', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
