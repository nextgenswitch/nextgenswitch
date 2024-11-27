<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Ticket;
use App\Models\TicketFollowUp;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Schema;

class TicketsController extends Controller
{

    /**
     * Display a listing of the tickets.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';

        // return getRoleNames
        $ticket = Ticket::where('organization_id', auth()->user()->organization_id)->where(function($query){
            $query->where('user_id', auth()->id())->orWhereNull('user_id');
        });          

        if(!empty($q))  $ticket->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $ticket->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $ticket->orderBy($sorta[0],$sorta[1]);
        }else
            $ticket->orderBy('created_at','DESC'); 
        




        $tickets = $ticket->paginate($perPage);
        
        $tickets->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'tickets.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new Ticket)->getTable());  
               
                $callback = function() use($tickets, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($tickets as $ticket) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $ticket->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('tickets.table', compact('tickets'));
        } 
        
        return view('tickets.index', compact('tickets'));


    }

    /**
     * Show the form for creating a new ticket.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        $users = User::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        
        if($request->ajax())
            return view('tickets.form', compact('users'))->with(['action'=>route('tickets.ticket.store'),'ticket' => null,'method'=>'POST']);
        else
            return view('tickets.create', compact('users'));
    }

    /**
     * Store a new ticket in the storage.
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
            
            Ticket::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);


            return redirect()->route('tickets.ticket.index')
                ->with('success_message', __('Ticket was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified ticket.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);
        $users = User::pluck('name','id')->all();

        if($request->ajax())
            return view('tickets.form', compact('ticket', 'users'))->with(['action'=>route('tickets.ticket.update',$id),'method'=>'PUT']);
        else
            return view('tickets.edit', compact('ticket', 'users'));
    }

    /**
     * Update the specified ticket in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    { 
        $data = $this->getData($request);
        
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
            if($request->ajax())    return response()->json(['success'=>true]);
        return redirect()->route('tickets.ticket.index')
            ->with('success_message', __('Ticket was successfully updated.'));            
    }


    public function show($id){
        $ticket = Ticket::with('followUps')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function followUp(Request $request, $id){
        $data = $request->validate(['comment' => 'required|string']);
        $data['user_id'] = auth()->id();
        $data['ticket_id'] = $id;

        TicketFollowUp::create($data);

        return redirect()->route('tickets.ticket.show', $id);
    }

    /**
     * Remove the specified ticket from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('tickets.ticket.index')
                ->with('success_message', __('Ticket was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified ticket for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $ticket = Ticket::findOrFail($id);  
          
            $ticket->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified ticket for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                Ticket::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new Ticket)->getTable(), $field) )
                          Ticket::whereIn('id',$ids)->update([$field=>$val]);
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
            'user_id' => 'nullable',
            'name' => 'nullable|string|min:0|max:191',
            'phone' => 'required|string|min:1|max:191',
            'subject' => 'required|string|min:1|max:191',
            'description' => 'required',
            'status' => 'required|numeric|min:1', 
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
