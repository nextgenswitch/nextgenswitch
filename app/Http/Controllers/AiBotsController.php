<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AiBot;
use App\Models\VoiceFile;
use App\Models\AiConversation;
use App\Models\TtsProfile;
use Illuminate\Http\Request;
use Exception;
use Schema;
use App\Models\Func;
use App\Models\AiAssistantCall;
use App\Http\Traits\FuncTrait;
class AiBotsController extends Controller
{
    use FuncTrait;

    /**
     * Display a listing of the ai bots.
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
        $aiBot = AiBot::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $aiBot->where('name', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $aiBot->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $aiBot->orderBy($sorta[0],$sorta[1]);
        }else
            $aiBot->orderBy('created_at','DESC'); 
        




        $aiBots = $aiBot->paginate($perPage);
        
        $aiBots->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'ai_assistants.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new AiBot)->getTable());  
               
                $callback = function() use($aiBots, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($aiBots as $aiBot) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $aiBot->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
    
        if($request->ajax()){
            return view('ai_bots.table', compact('aiBots'));
        } 
        
        return view('ai_bots.index', compact('aiBots'));


    }

    public function destinations($function){

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }           

        die();

    }

    /**
     * Show the form for creating a new ai bot.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $functions = Func::getFuncList();
        $destinations = array();
        
        $tts_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 0)->pluck('name', 'id');
        $stt_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 1)->pluck('name', 'id');
        $llm_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 2)->pluck('name', 'id');

        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        if($request->ajax())
            return view('ai_bots.form', compact('functions', 'destinations', 'voices', 'tts_profiles', 'stt_profiles', 'llm_profiles'))->with(['action'=>route('ai_bots.ai_bot.store'),'aiBot' => null,'method'=>'POST']);
        else
            return view('ai_bots.create', compact('functions', 'destinations', 'voices', 'tts_profiles', 'stt_profiles', 'llm_profiles'));
    }

    

    /**
     * Store a new ai bot in the storage.
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

            AiBot::create($data);
            if($request->ajax())  return response()->json(['success'=>true]);
            return redirect()->route('ai_bots.ai_bot.index')
                ->with('success_message', __('Ai Bot was successfully added.'));
      
    }


    /**
     * Show the form for editing the specified ai bot.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $aiBot = AiBot::findOrFail($id);
        if(! AiBot::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();
        
        $functions = Func::getFuncList();
        $destinations = $this->dist_by_function( $aiBot->function->func, 0, true );
        $voices = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name','id')->all();
        $tts_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 0)->pluck('name', 'id');
        $stt_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 1)->pluck('name', 'id');
        $llm_profiles = TtsProfile::where('organization_id', auth()->user()->organization_id)->where('type', 2)->pluck('name', 'id');

        if($request->ajax())
            return view('ai_bots.form', compact('aiBot', 'functions', 'destinations', 'voices', 'tts_profiles', 'stt_profiles', 'llm_profiles'))->with(['action'=>route('ai_bots.ai_bot.update',$id),'method'=>'PUT']);
        else
            return view('ai_bots.edit', compact('aiBot', 'functions', 'destinations', 'voices', 'tts_profiles', 'stt_profiles', 'llm_profiles'));
    }

    /**
     * Update the specified ai bot in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        $aiBot = AiBot::findOrFail($id);

        $func = Func::select('id')->where('func', $data['function_id'])->first();

        $data['function_id'] = $func->id;
        $data['create_support_ticket'] = $request->has('create_support_ticket') ? 1 : 0;
        
        if(! AiBot::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            
            $aiBot->update($data);
             if($request->ajax())    return response()->json(['success'=>true]);
            return redirect()->route('ai_bots.ai_bot.index')
                ->with('success_message', __('Ai Bot was successfully updated.'));
            
    }

    /**
     * Remove the specified ai bot from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id,Request $request)
    {
        try {
            $aiBot = AiBot::findOrFail($id);
            if(! AiBot::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
	        return back();
        
            $aiBot->delete();

            if($request->ajax()) return response()->json(['success'=>true]);
            else  return redirect()->route('ai_bots.ai_bot.index')
                ->with('success_message', __('Ai Bot was successfully deleted.'));
        } catch (Exception $exception) {

            if($request->ajax())  return response()->json(['success'=>false]);
            else   return back()->withInput()
                ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
        }
    }

    /**
     * update the specified ai bot for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id,Request $request){
          
        try {
            $aiBot = AiBot::findOrFail($id);  
          
            $aiBot->update($request->all());

           
            return response()->json(['success'=>true]);
                   
        } catch (Exception $exception) {
            return response()->json(['success'=>false]);
        }

    }

    /**
     * update the specified ai bot for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request){
        
       try{
           
            $data = $request->all();
            $ids =  explode(',',$data['ids']);
            if(isset($data['mass_delete']) && $data['mass_delete'] == 1){
                AiBot::whereIn('id',$ids)->delete();
            }else{

                foreach($data as $field=>$val){

                      if(!in_array($field, ['ids','_token','_method','mass_delete'])  && Schema::hasColumn((new AiBot)->getTable(), $field) )
                          AiBot::whereIn('id',$ids)->update([$field=>$val]);
                }
            }
            return response()->json(['success'=>true]);

       } catch (Exception $exception) {
            return response()->json(['success'=>false]);
       }

       
    }    


    public function assistantCalls(Request $request){

        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $aiAssistantCall = AiAssistantCall::where('organization_id', auth()->user()->organization_id);
        if(!empty($q))  $aiAssistantCall->where('caller_id', 'LIKE', '%' . $q . '%');

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $aiAssistantCall->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $aiAssistantCall->orderBy($sorta[0],$sorta[1]);
        }else
            $aiAssistantCall->orderBy('created_at','DESC'); 
        




        $aiAssistantCalls = $aiAssistantCall->paginate($perPage);
        
        $aiAssistantCalls->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'ai_assistant_calls.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                $columns = ['date', 'caller_id','ai_assistant']; // specify columns if need
                
               
                $callback = function() use($aiAssistantCalls, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($aiAssistantCalls as $call) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column){
                            if($column == 'date'){
                                $row['date'] = $call->created_at;
                            }
                            else if($column == 'ai_assistant'){
                                $row['AI assistant']  = $call->ai_assistant->name;
                            }
                            else{
                                $row[$column] = $call->{$column};
                            }
                        }
                             

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

        
                

        if($request->ajax()){
            return view('ai_bots.ai_assis_call_table', compact('aiAssistantCalls'));
        } 
        
        $aiList = AiBot::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');

        
        return view('ai_bots.ai_assis_call', compact('aiAssistantCalls', 'aiList'));

    }

    public function conversations($call_id){
        $conversations = AiConversation::where('call_id', $call_id)->get();

        if(request()->ajax()){
            return view('ai_bots.conversation', compact('conversations'));
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
                'voice_id' => 'required|numeric',
                'waiting_tone' => 'nullable|numeric',
                'inaudible_voice' => 'nullable|numeric',
                'listening_tone' => 'nullable|numeric',
                'call_transfer_tone' => 'nullable|numeric',
            'llm_provider_id' => 'required|numeric',
            'resource' => 'required|string|min:1|max:4294967295', 
            'email' => 'nullable|string|email',
            'create_support_ticket' => 'nullable',
            'max_interactions' => 'nullable',
            'max_silince' => 'nullable',
            // 'internal_directory' => 'required',
            'function_id' => 'required|string',
            'destination_id' => 'required|string',
            'stt_profile_id' => 'nullable|numeric',
            'tts_profile_id' => 'nullable|numeric'
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
