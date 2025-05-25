<?php
namespace App\Http\Controllers\Api\Functions;
use App\Models\Ticket;
use App\Models\AiBot;
use App\Tts\Tts;
use App\Models\AiAssistantCall;
use App\Models\AiConversation;
use App\Http\Controllers\Api\FunctionCall;


class AiAssistantHandler {
    protected $response;
    protected $func_id;
    protected $modify = false;

    public function __construct($func_id, $response) {
        $this->func_id = $func_id;
        $this->response = $response;
    }

    public function handle($id) {
        $assistant = AiBot::find($id);
        $data = request()->all();
        
        $this->logRequestData($data);
        $secondsAgo = $this->handleAiAssistantCall($assistant, $data);
        info("Call is eastablished {$secondsAgo} Ago");

        if($secondsAgo > $assistant->max_interactions){
            
            $this->transferCall($assistant, $data, $this->extractCallNumber($assistant->resource));
            return;
        } 

        if (request()->query('gather') == '1') {
            $this->handleGather($assistant, $data);
        } elseif (request()->query('process') == '1') {
            $this->handleProcess($assistant, $data);
        } elseif (request()->query('loop') == '1') {
            $this->handleLoop($id);
        }
        else{
            $this->playWelcomeVoice($assistant);
        }

        $options = [
            'action'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$assistant->id, 'gather'=>'1' ]),
            'speechTimeout'=>$assistant->max_silince,
            'transcript'=>false, 
            'input' => 'speech'
        ];

        $this->response->gather($options);

        if($this->modify){
            info('Modifing call');
            FunctionCall::modify_call($data['call_id'],['responseXml'=>$this->response->xml()]);
            return;
        }

        FunctionCall::execute($assistant->function_id, $assistant->destination_id, $this->response);

        return $this->response;
    }

    protected function logRequestData($data) {
        info('Log data from request');
        info($data);
    }

    protected function handleAiAssistantCall($assistant, $data) {
        info('Storing AI call into database');
        if (isset($data['event_call_id'])) {
            $aiAssisCall = AiAssistantCall::firstOrCreate([
                'call_id' => $data['event_call_id']
            ], [
                'organization_id' => $assistant->organization_id,
                'ai_assistant_id' => $assistant->id,
                'caller_id' => $data['event_from'],
            ]);

            return  $aiAssisCall->created_at->diffInSeconds(now());
            
        }

        return 0;
    }

    protected function handleGather($assistant, $data) {
        info('Gather user voice');
        if (isset($data['voice'])) {
            $this->processVoice($assistant, $data);
        } else {
            if ($assistant->inaudible_voice) {
                $this->response = FunctionCall::voice_file_play($this->response, $assistant->inaudible_tone);
            }
        }
    }

    protected function processVoice($assistant, $data) {
        info('Process voice from user voice');
        $workerPayload = [
            'delay' => 1,
            'data' => ['call_id' => $data['call_id'], 'voice' => $data['voice']],
            'url' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $assistant->id, 'process' => 1])
        ];
        FunctionCall::create_worker($workerPayload);

        FunctionCall::voice_file_play($this->response, $assistant->waiting_voice);
        $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $assistant->id, 'loop' => 1]));

    }

    protected function handleProcess($assistant, $data) {
        info('handle process request');
        $stttext = Tts::speechToText(storage_path('app/public/' . $data['voice']), $assistant->organization_id,$assistant->stt_profile_id);
        

        if ($stttext) {   
            info("STT response: ", $stttext); 
            AiConversation::create([
                'call_id' => $data['call_id'],
                'message' => $stttext['text'],
                'ai_msg' => 0
            ]);
            
            $this->generateAssistantResponse($assistant, $data, $stttext['text']);
        } else {
            $this->handleInaudibleResponse($assistant);
        }

        $this->modify = true;
    }

    protected function generateAssistantResponse($assistant, $data, $userInput) {
        info('Generating assistant response');
       
         
        $previousConversations = AiConversation::where('call_id', $data['call_id'])->orderBy('created_at', 'asc')->get();
        
        $history = "";
        foreach ($previousConversations as $conversation) {
            $history .= $conversation->ai_msg ? "AI: {$conversation->message}\n" : "User: {$conversation->message}\n";
        }

        // Construct prompt with context
        // $instructions = "You are a virtual telephone assistant. Maintain context and continue the conversation logically. Here is the conversation history:\n\n" . $history . "\n\nUser: $userInput\nAI:";
        
       
        // $instructions = "As a virtual voice assistant, detect user intent for live agent requests based on specific keywords or frustration cues. If the user explicitly requests to talk with a live agent, respond only with [LIVE_AGENT_REQUESTED]. Otherwise, provide concise answers using $assistant->resource. Never include [LIVE_AGENT_REQUESTED] in any other response. Ensure responses are conversational, clear, efficient, and contextually relevant.";
       $instructions = "You are a LLM that work as a virtual telephone assistant, detect user intent for live agent requests based on specific keywords or frustration cues. Do not share contact number with customer directly . If the user explicitly requests to talk with a live agent, respond only with  a tag like <call>0171717137361</call> . Please provide concise answers using following contents: \n $assistant->resource Here is the conversation history:\n\n $history \n\nUser: $userInput\nAI:"; 
       $ans = Tts::llm($userInput, $instructions, $assistant->organization_id, $assistant->llm_provider_id);
        
        info("LLM response " . $ans);

        if ($ans) {
            $number = $this->extractCallNumber($ans);
            info('Present contact number in response' . $number);

            //$ans = $this->removeFormatting($ans);
            AiConversation::create([
                'call_id' => $data['call_id'],
                'message' => $ans,
                'ai_msg' => 1
            ]);
            
            if ($number) {
                $this->transferCall($assistant, $data, $number);
                return;
            }
            
            $this->response->say($ans,['profile'=>$assistant->tts_profile_id]);
        } else {
            $this->redirectToLastDestination($assistant, $data);
        }
    }

    private function extractCallNumber($text){
        if (preg_match('/<call>(.*?)<\/call>/', $text, $match)) {
            $number = trim($match[1]);  
            $number = preg_replace('/\D/', '', $number);  
            return $number !== '' ? $number : false;
        }
        return false;
    }

    protected function transferCall($assistant, $data, $phoneNo) {
        info('Transferring call to support center');
        $this->notifyAndCreateTicket($assistant, $data);

        FunctionCall::voice_file_play($this->response, $assistant->call_transfer_tone);
        $this->response->dial($phoneNo);
    }

    protected function redirectToLastDestination($assistant, $data) {
        info('Redirect to last destination');
        $this->notifyAndCreateTicket($assistant, $data);
        $this->response->redirect(route('api.func_call', [
            'func_id' => $assistant->function_id,
            'dest_id' => $assistant->destination_id
        ]));
    }

    protected function handleLoop($id) {
        info('Handle loop');
        $this->response->pause(5);
        $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id, 'loop' => 1]));
        return $this->response;
    }

    protected function handleInaudibleResponse($assistant) {
        info('playing inaudible voice');
        FunctionCall::voice_file_play($this->response, $assistant->inaudible_tone);
    }

    protected function playWelcomeVoice($assistant) {
        info('playing welcome voice');
        FunctionCall::voice_file_play($this->response, $assistant->welcome_voice);
    }

    private function notifyAndCreateTicket($assistant, $data){
        info('notify and create support ticket');
        if($assistant->email){
            $conversations = AiConversation::where('call_id', $data['call_id'])->get();
            FunctionCall::send_mail([
                'to' => $assistant->email,
                'subject' => 'Received ai assistant conversation from ' . $assistant->name,
                'body' => $conversations,
                'template' => 'conversation',
                'organization_id' => $assistant->organization_id
            ]);
        }

        // create ticket
        if( $assistant->create_support_ticket ){
            $aiAssisCall = AiAssistantCall::where('call_id', $data['call_id'])->first();
            $conversations = AiConversation::where('call_id', $data['call_id'])->get();

            $contents = '';
            foreach($conversations as $conversation) 
                $contents .= $conversation->ai_msg ? sprintf("<p> <b>AI:</b> %s <p>", $conversation->message) : sprintf("<p> <b>Customer:</b> %s <p>", $conversation->message);

            Ticket::create([
                'organization_id' => $assistant->organization_id,
                'name'=>$aiAssisCall->caller_id,
                'phone' => $aiAssisCall->caller_id,
                'subject' => __('Support ticket from ') . $aiAssisCall->caller_id,
                'description' => $contents,
            ]);

        }
    }
    
    public function removeFormatting($text){
        return strip_tags(preg_replace('/\\(.?)\\|\[(.?)\]\((.*?)\)/', '$1$2', $text));
    }
}
