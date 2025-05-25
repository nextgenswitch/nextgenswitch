<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Func;
use App\Models\Organization;
use App\Models\VoiceFile;
use Illuminate\Http\Request;
use App\Http\Traits\FuncTrait;
use Exception;
use Schema;

class AnnouncementsController extends Controller
{

    use FuncTrait;
    /**
     * Display a listing of the announcements.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-incoming']);  
    } 

    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $announcement = Announcement::with('voice','function')->where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $announcement->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $announcement->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $announcement->orderBy($sorta[0],$sorta[1]);
        }else
            $announcement->orderBy('created_at','DESC'); 
        




        $announcements = $announcement->paginate($perPage);
        
        $announcements->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        /*
        if(!empty($request->get('csv'))){

            $fileName = 'announcements.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new Announcement)->getTable());  
               
                $callback = function() use($announcements, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($announcements as $announcement) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $announcement->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        */      
                

        if($request->ajax()){
            return view('announcements.table', compact('announcements'));
        } 
        
        return view('announcements.index', compact('announcements'));


    }

    public function destinations($function){

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }           

        die();

    }


    /**
     * Show the form for creating a new announcement.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        $functions = Func::getFuncList();
        $destinations = array();
        
        if($request->ajax())
            return view('announcements.form', compact('voices','functions', 'destinations'))->with(['action'=>route('announcements.announcement.store'),'announcement' => null,'method'=>'POST']);
        else
            return view('announcements.create', compact('voices','functions', 'destinations'));
    }

    /**
     * Store a new announcement in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
            $data = $this->getData($request);
            $data['organization_id'] = auth()->user()->organization_id;
            $func = Func::select('id')->where('func', $data['function_id'])->first();

            $data['function_id'] = $func->id;

            Announcement::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('announcements.announcement.index')
                ->with('success_message', __('Announcement was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified announcement.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $announcement = Announcement::findOrFail($id);

        if(! Announcement::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();
        
        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        $functions = Func::getFuncList();
        $destinations = $this->dist_by_function( $announcement->function->func, 0, true );

        if($request->ajax())
            return view('announcements.form', compact('announcement','voices','functions','destinations'))->with(['action'=>route('announcements.announcement.update',$id),'method'=>'PUT']);
        else
            return view('announcements.edit', compact('announcement',   'voices','functions','destinations'));
    }

    /**
     * Update the specified announcement in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
            $data = $this->getData($request);
            $func = Func::select('id')->where('func', $data['function_id'])->first();

            $data['function_id'] = $func->id;
            
            $announcement = Announcement::findOrFail($id);
        
            if(! Announcement::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();

            $announcement->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('announcements.announcement.index')
                ->with('success_message', __('Announcement was successfully updated.'));
            
    }

    /**
     * Remove the specified announcement from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            if(! Announcement::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();

            $announcement->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('announcements.announcement.index')
                ->with('success_message', __('Announcement was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()           
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified announcement for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $announcement = Announcement::findOrFail($id);  
            if(! Announcement::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();
          
            $announcement->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified announcement for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Announcement::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Announcement)->getTable(), $field) )
                          Announcement::whereIn('id',$ids)->update([$field=>$val]);
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
            'voice_id' => 'required',
            'function_id' => 'required',
            'destination_id' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
