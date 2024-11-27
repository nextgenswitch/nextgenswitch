<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Schema;

class NotificationsController extends Controller
{

    /**
     * Display a listing of the notifications.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        

        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $notification = Notification::where('notifiable_id','=',Auth::id());
        if(!empty($q))  $notification->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $notification->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $notification->orderBy($sorta[0],$sorta[1]);
        }else
            $notification->orderBy('created_at','DESC'); 
        




        $notifications = $notification->paginate($perPage);
        
        $notifications->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'notifications.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );



                $columns = Schema::getColumnListing((new Notification)->getTable());  
               
                $callback = function() use($notifications, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($notifications as $notification) {
            
                        
                        foreach($columns as $column)
                             $row[$column] = $notification->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
        auth()->user()->unreadNotifications->markAsRead();        

        if($request->ajax()){
            return view('notifications.table', compact('notifications'));
        } 
        
        return view('notifications.index', compact('notifications'));


    }

    /**
     * Show the form for creating a new notification.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $users = User::pluck('name','id')->all();
        
        if($request->ajax())
            return view('notifications.form', compact('users'))->with(['action'=>route('notifications.notification.store'),'notification' => null,'method'=>'POST']);
        else
            return view('notifications.create', compact('users'));
    }

    /**
     * Store a new notification in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
   
            
            $data = $this->getData($request);
            
            Notification::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('notifications.notification.index')
                ->with('success_message', 'Notification was successfully added.');
      
    }


    /**
     * Show the form for editing the specified notification.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $notification = Notification::findOrFail($id);
        $users = User::pluck('name','id')->all();

        if($request->ajax())
            return view('notifications.form', compact('notification','users'))->with(['action'=>route('notifications.notification.update',$id),'method'=>'PUT']);
        else
            return view('notifications.edit', compact('notification','users'));
    }

    /**
     * Update the specified notification in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
     
            
            $data = $this->getData($request);
            
            $notification = Notification::findOrFail($id);
            $notification->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('notifications.notification.index')
                ->with('success_message', 'Notification was successfully updated.');
            
    }

    /**
     * Remove the specified notification from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('notifications.notification.index')
                ->with('success_message', 'Notification was successfully deleted.');
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * update the specified notification for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $notification = Notification::findOrFail($id);  
          
            $notification->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified notification for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Notification::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Notification)->getTable(), $field) )
                          Notification::whereIn('id',$ids)->update([$field=>$val]);
                }
            }
            return response()->json(['success'=>true]);

       } catch (Exception $exception) {
            return response()->json(['success'=>false]);
       }

       
    } 
    
    
    public function seenNotification($ids){
        if(request()->ajax()){
           /*  $ids = explode(',', $ids);
            Notification::whereIn('id',$ids)->update(['is_seen' => '1']); */
            Auth::user()->unreadNotifications()->take(4)->update(['read_at' => now()]);
            return response(['status' => 'success']);
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
                'user_id' => 'required',
            'message_type' => 'required|numeric|string|min:1|max:1000',
            'message_code' => 'required|numeric|min:-2147483648|max:2147483647',
            'message' => 'required|numeric|string|min:1|max:191', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
