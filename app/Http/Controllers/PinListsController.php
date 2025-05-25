<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\PinList;
use Illuminate\Http\Request;
use Exception;
use Schema;

class PinListsController extends Controller
{

    /**
     * Display a listing of the pin lists.
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
        $pinList = PinList::with('organization');
        if(!empty($q))  $pinList->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $pinList->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $pinList->orderBy($sorta[0],$sorta[1]);
        }else
            $pinList->orderBy('created_at','DESC'); 
        

        if(!empty($request->get('csv'))){

            $fileName = 'pinLists.csv';
            $pinLists = $pinList->get();

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                $columns = ['name','pin_list']; // specify columns if need

                $callback = function() use($pinLists, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($pinLists as $pinList) {
                        foreach($columns as $column)
                             $row[$column] = $pinList->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        $pinLists = $pinList->paginate($perPage);
        $pinLists->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 
                

        if($request->ajax()){
            return view('pin_lists.table', compact('pinLists'));
        } 
        
        return view('pin_lists.index', compact('pinLists'));


    }

    /**
     * Show the form for creating a new pin list.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
     
        
        if($request->ajax())
            return view('pin_lists.form')->with(['action'=>route('pin_lists.pin_list.store'),'pinList' => null,'method'=>'POST']);
        else
            return view('pin_lists.create');
    }

    /**
     * Store a new pin list in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            
            PinList::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('pin_lists.pin_list.index')
                ->with('success_message', __('Pin List was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified pin list.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        if(! PinList::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

        $pinList = PinList::findOrFail($id);
        $organizations = Organization::pluck('name','id')->all();

        if($request->ajax())
            return view('pin_lists.form', compact('pinList'))->with(['action'=>route('pin_lists.pin_list.update',$id),'method'=>'PUT']);
        else
            return view('pin_lists.edit', compact('pinList'));
    }

    /**
     * Update the specified pin list in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            if(! PinList::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $pinList = PinList::findOrFail($id);
            $pinList->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('pin_lists.pin_list.index')
                ->with('success_message', __('Pin List was successfully updated.'));
            
    }

    /**
     * Remove the specified pin list from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            if(! PinList::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $pinList = PinList::findOrFail($id);
            $pinList->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('pin_lists.pin_list.index')
                ->with('success_message', __('Pin List was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified pin list for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            
            if(! PinList::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();

            $pinList = PinList::findOrFail($id);  
          
            $pinList->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified pin list for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                PinList::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new PinList)->getTable(), $field) )
                          PinList::whereIn('id',$ids)->update([$field=>$val]);
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
            'pin_list' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
