<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SipUser;
use App\Models\Call;
use App\Models\Func;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Models\CallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Traits\FuncTrait;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\Functions\CallHandler;
use Illuminate\Http\JsonResponse;


class DialerController extends Controller
{
    use FuncTrait;

    public function login(Request $request)
    {

        if ($request->ajax()) {

            $rules = [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $validationErrors = $validator->errors()->toArray();

                return response()->json(['status' => 'error', 'errors' => $this->getErrors($validationErrors)]);
            }

            $data = $validator->validated();

            $sip = SipUser::where('username', $data['username'])->where('password', $data['password'])
                ->where('organization_id', auth()->user()->organization_id)->where('peer', 0)
                ->where('status', 1)->whereRaw('NOT(user_type <=> 2)');
            $error = 'User or password incorrent';
            if ($sip->exists()) {
                $sipuser = $sip->first();

                //$client_id = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
                $call = FunctionCall::send_call(['to' => $sipuser->username, 'channel_id' => $sipuser->id, 'from' => 'easypbx', 'response' => route('webdialer.response'), 'statusCallback' => route('webdialer.responseCallback', 'webdialer')]);
                // return $call;
                // dd($call);

                if (isset($call['error']) && $call['error'] == true) {
                    $error = $call['error_message'];
                } else if (isset($call['status-code']) && ($call['status-code'] < CallStatusEnum::Disconnected->value)) {

                    $request->session()->put('dialer.login.' . auth()->user()->organization_id, $sipuser->username);
                    $request->session()->put('dialer.call_id.' . auth()->user()->organization_id, $call['call_id']);

                    return response()->json(['status' => 'success', 'call_id' => $call['call_id']]);
                } else $error = 'User not active . Please login to your dialer.';
            }
            return response()->json(['status' => 'error', 'errors' => ['username' => $error]]);
        }
    }

    public function logout()
    {
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $response = new VoiceResponse();
        $response->hangup();
        FunctionCall::modify_call($call_id, ['responseXml' => $response->xml()]);
        request()->session()->forget('dialer.call_id.' . auth()->user()->organization_id);
        request()->session()->put('dialer.call_id.' . auth()->user()->organization_id);
    }

    function isLoggedIn()
    {
        if (request()->session()->has('dialer.login.' . auth()->user()->organization_id)) {
            $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
            $call = Call::find($call_id);
            if ($call && $call->status == CallStatusEnum::Established)
                return true;
        }
        return false;
    }

    public function loginForm()
    {
        return view('dialer.login', ['call_id' => 'dsfsd']);
    }

    public function index()
    {
        if (request()->session()->has('dialer.login.' . auth()->user()->organization_id)) {
            $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
            //info("dialer outgoing call id on index " . session('dialer.outgoing.call_id'));

            $outgoingCall = [];
            $oCall = Call::where('parent_call_id', $call_id)->where('status', '<', CallStatusEnum::Disconnected->value)->first();
            //info("on index");
            //info($oCall);
            if ($oCall) {

                $outgoingCall = CallHandler::prepare_call_json($oCall);
                //info($outgoingCall);
                if (count($outgoingCall) > 0 && $outgoingCall['status-code'] >= CallStatusEnum::Disconnected->value) {
                    $outgoingCall = [];
                    //session()->forget('dialer.outgoing.call_id');
                }
            }

            $call = Call::find($call_id);
            if ($call && $call->status->value < CallStatusEnum::Disconnected->value) {
                $dialer_functions = Func::getFuncList();
                return view('dialer.index', ['dialer_functions' => $dialer_functions, 'outgoingCall' => $outgoingCall, 'call_id' => $call_id, 'login' => session()->get('dialer.login.' . auth()->user()->organization_id)]);
            } else
                $this->logout();
        }

        return view('dialer.login', ['call_id' => 'dsfsd']);
    }

    public function destinations($function)
    {

        if (request()->ajax()) {
            return $this->dist_by_function($function);
        }

        die();
    }


    /*  public function dial(){

        $tel_no = request()->query('tel_no');
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $dialercall = Call::find($call_id);
        info($dialercall);
        $call = FunctionCall::send_call(['to'=>$tel_no,'from'=>$dialercall->destination,'response'=>route('webdialer.response'),'statusCallback'=>route('webdialer.responseCallback', ['client_id' => $call_id, 'organization_id' => auth()->user()->organization_id])]);
        if(isset($call['error'])){
            FunctionCall::send_to_websocket($call_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=> CallStatusEnum::Failed]]);
            return $call;
        }

        session(['dialer.outgoing.call_id' => $call['call_id']]);

        $voice_response = new VoiceResponse;
        $voice_response->bridge($call['call_id']);
        $voice_response->redirect(route('webdialer.response'));
        FunctionCall::modify_call($call_id,['responseXml'=>$voice_response->xml()]);
        $call['error'] = false;
        return $call;
    }  */

    public function dial()
    {
        $tel_no = request()->query('tel_no');
        $record = request()->query('record', true);
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $call = Call::find($call_id);
        $voice_response = new VoiceResponse;
        // session(['dialer.outgoing.tel_no' => $tel_no]);
        $voice_response->dial($tel_no, ['record' => $record, 'callerId' => $call->destination, 'action' => route('webdialer.statusCallback', ['client_id' => $call_id]), 'statusCallback' => route('webdialer.responseCallback', ['client_id' => $call_id, 'tel_no' => $tel_no])]);
        $voice_response->redirect(route('webdialer.response'));
        FunctionCall::modify_call($call_id, ['responseXml' => $voice_response->xml()]);
        return ['success' => true];
    }

    public function hangup()
    {
        $response = new VoiceResponse();
        //$response->hangup();
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $response->redirect(route('webdialer.response'));
        return FunctionCall::modify_call($call_id, ['responseXml' => $response->xml()]);
    }



    public function forward(Request $request)
    {
        $response = new VoiceResponse();

        if ($request->has('forward')) {
            $response->dial($request->input('forward'));
        } else if ($request->has('function_id') && $request->has('destination_id')) {
            $func = Func::select('id')->where('func', $request->input('function_id'))->first();

            $response->redirect(route('api.func_call', [
                'func_id' => $func->id,
                'dest_id' => $request->input('destination_id')
            ]));
        }

        $response->hangup();
        FunctionCall::modify_call(request()->get('call_id'), ['responseXml' => $response->xml()]);



        //FunctionCall::send_to_websocket(request()->session()->get('dialer.call_id.' . auth()->user()->organization_id),['type'=>1,'data'=>['status'=>'Disconnected','call_id'=>'','status-code'=>3]]);
    }

    public function getErrors($validationErrors)
    {

        $errors = [];

        foreach ($validationErrors as $field => $error) {

            if (isset($error[0])) {
                $errors[$field] = $error[0];
            }
        }

        return $errors;
    }

    public function dialer_connect_response()
    {
        //info("dialer connect response");
        //info(request()->input());
        $voice_response = new VoiceResponse;
        $voice_response->pause(10);
        $voice_response->redirect(route('webdialer.response'));
        return $voice_response->xml();
    }

    public function dial_status_callback($client_id)
    {
        $calldata = request()->input();
        info("dialer action  callback " . $client_id);
        $call = Call::find($calldata['call_id']);


        if ($calldata['bridge_call_id'] != '') {
            $call_history = [
                'organization_id' => $call->organization_id,
                'call_id' => $calldata['call_id'],
                'bridge_call_id' => $calldata['bridge_call_id'],
                'duration' => $calldata['duration'],
                'record_file' => isset($calldata['record_file']) ? $calldata['record_file'] : '',
                'status' => ($calldata['dial_status'] == 1) ? CallStatusEnum::Disconnected->value : CallStatusEnum::Failed->value
            ];
            CallHistory::create(
                $call_history
            );

            $call = Call::find($calldata['bridge_call_id']);
            $call_resp = CallHandler::prepare_call_json($call, false);
            $call_resp['duration'] = $calldata['duration'];
            $call_resp['status-code'] = $call_history['status'];
            $call_resp['status'] = ($calldata['dial_status'] == 1) ? CallStatusEnum::Disconnected->getText() : CallStatusEnum::Failed->getText();
            $call_resp['record_file'] = isset($calldata['record_file']) ? $calldata['record_file'] : '';
            FunctionCall::send_to_websocket($client_id, ['type' => 1, 'data' => $call_resp]);
        } else
            FunctionCall::send_to_websocket($client_id, ['type' => 1, 'data' => ['status' => 'Failed', 'call_id' => '', 'status-code' => 3]]);


        return $this->dialer_connect_response();
    }


    public function dialer_response_callback($client_id)
    {
        $calldata = request()->input();

        info("dialer response callback");
        Log::info($calldata);


        $type = 1;
        if ($client_id == 'webdialer') {
            $client_id = $calldata['call_id'];
            $type = 0;
            if ($calldata['status-code'] >= CallStatusEnum::Disconnected->value) {
                // info("dialer disconnecting");
                //$this->logout();
            }
        }



        $data = ['type' => $type, 'data' => $calldata];
        FunctionCall::send_to_websocket($client_id, $data);
    }


    public function web()
    {
        return view('dialer.web.index');
    }

    public function callHistory(Request $request, string $rawNumber): JsonResponse
    {
        $orgId  = auth()->user()->organization_id;
        $number = $this->normalizePhone($rawNumber);
        $search = trim((string) $request->input('q', ''));

        // Single, reusable query builder (eager-load bridgeCall since we read it)
        $calls = $this->callListQuery($orgId, $number, $search, 20)
            ->with('bridgeCall')
            ->get();

        // Map to API shape (same as before)
        $history = [];
        foreach ($calls as $c) {
            $history[] = $this->mapCallToHistoryItem($c, $orgId);
        }

        return response()->json(['number' => $number, 'history' => $history], 200);
    }

    public function contacts(Request $request): JsonResponse
    {
        $orgId  = auth()->user()->organization_id;
        $search = trim((string) $request->input('q', ''));

        $contacts = Contact::where('organization_id', $orgId)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name',  'like', "%{$search}%")
                        ->orWhere('tel_no',     'like', "%{$search}%")
                        ->orWhere('email',      'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->limit(20)
            ->get();

        $results = [];
        foreach ($contacts as $c) {
            $fullName = trim((string) (($c->first_name ?? '') . ' ' . ($c->last_name ?? '')));
            if ($fullName === '') $fullName = 'Unknown';

            $phone = $this->normalizePhone((string) ($c->tel_no ?? ''));

            $results[] = [
                'id'     => $c->id,
                'name'   => $fullName,
                'phone'  => $phone ?: '—',
                'email'  => $c->email ?: '—',
                'avatar' => $this->initialsFromName($fullName) ?? 'UK',
            ];
        }

        return response()->json(['results' => $results], 200);
    }
  /*   public function customerLookup(string $rawNumber, $organizationId = null): JsonResponse
    {
        $data = Contact::customerLookup($rawNumber, $organizationId);
        return response()->json($data, 200);
    }
 */
    private function normalizePhone(string $number): string
    {
        return preg_replace('/[^\d+]/', '', $number) ?? $number;
    }

       private function callListQuery(int|string $orgId, string $number, ?string $search, int $limit)
    {
        $search = $search !== null ? trim($search) : null;

        return Call::where('organization_id', $orgId)
            ->where(function ($q) use ($number, $search) {
                $q->where('caller_id', $number)
                    ->where('uas', 0);

                if ($search !== null && $search !== '') {
                    // branch with q: destination LIKE %q%
                    $q->where('destination', 'like', "%{$search}%");
                }
            })
            ->orWhere(function ($q) use ($number, $search) {
                $q->where('destination', $number)
                    ->where('uas', 1);

                if ($search !== null && $search !== '') {
                    // branch with q: caller_id LIKE %q%
                    $q->where('caller_id', 'like', "%{$search}%");
                }
            })
            ->where('status', '>=', CallStatusEnum::Disconnected->value)
            ->latest('created_at')
            ->limit($limit);
    }


  
    public function customerLookup(string $rawNumber, $organizationId = null): JsonResponse
    {
        $orgId  = $organizationId ?? auth()->user()->organization_id;
        $number = $this->normalizePhone($rawNumber);

        // Tickets (unchanged logic; minor tidy)
        $ticketQuery = Ticket::where('organization_id', $orgId)
            ->where('phone', $number)
            ->where('status', 4);

        $ticketCount = (clone $ticketQuery)->count();
        $tickets     = (clone $ticketQuery)->latest('created_at')->take(5)->get();

        // Base response
        $response = [
            'name'        => 'Unknown',
            'phone'       => $number,
            'email'       => '—',
            'company'     => '—',
            'designation' => '—',
            'tags'        => [],
            'tickets'     => $ticketCount,
            'avatar'      => 'UK',
        ];

        // Prefer Lead; else Contact (unchanged behavior)
        $lead = Lead::where('organization_id', $orgId)
            ->where('phone', $number)
            ->first();

        if ($lead) {
            $response['name']        = $lead->name ?: 'Unknown';
            $response['phone']       = $lead->phone ?: $number;
            $response['email']       = $lead->email ?: '—';
            $response['company']     = $lead->company ?: '—';
            $response['designation'] = $lead->designation ?: '—';
            $response['avatar']      = $this->initialsFromName($lead->name) ?? 'UK';
        } else {
            $contact = Contact::where('organization_id', $orgId)
                ->where('tel_no', $number)
                ->first();

            if ($contact) {
                $fullName = trim(($contact->first_name ?? '') . ' ' . ($contact->last_name ?? ''));
                $response['name']   = $fullName !== '' ? $fullName : 'Unknown';
                $response['phone']  = $contact->tel_no ?: $number;
                $response['email']  = $contact->email ?: '—';
                $response['avatar'] = $this->initialsFromName($fullName) ?? 'UK';
            }
        }

        // Calls for timeline (eager-load bridgeCall since we read it)
        $calls = $this->callListQuery($orgId, $number, null, 10)
            ->with('bridgeCall')
            ->get();

        $timeline = collect();

        foreach ($calls as $c) {
            $isIncoming   = (int) ($c->uas ?? 0) === 1;
            $fromNumber   = $this->normalizePhone((string) optional($c)->caller_id);
            $toNumber     = $this->normalizePhone((string) optional($c)->destination);
            $statusText   = $c->status->getText();
            $rawDuration  = $c->duration;
            $durationStr  = function_exists('duration_format') ? duration_format($rawDuration) : (string) $rawDuration;

            // Counterparty + display name (same logic as before)
            $counterparty = $isIncoming ? ($fromNumber ?: $toNumber) : ($toNumber ?: $fromNumber);
            $who          = $this->displayNameForNumber($orgId, $counterparty) ?? ($counterparty ?: 'Unknown');

            $dirText     = $isIncoming ? 'Incoming' : 'Outgoing';
            $statusEnum  = $this->normalizeCallStatus(optional($c->bridgeCall)->status ?? $c->status ?? null);
            $qualifier   = $this->qualifierFromStatus($statusEnum, $rawDuration);

            $noteParts = [
                "{$dirText} call",
                $isIncoming ? 'from' : 'to',
                $who,
            ];
            if ($qualifier) {
                $noteParts[] = "— {$qualifier}";
            }
            if ($statusText !== '—') {
                $noteParts[] = "({$statusText})";
            }
            if (!empty($durationStr)) {
                $noteParts[] = "• {$durationStr}";
            }

            $timeline->push([
                'type'      => 'call',
                'direction' => $dirText,
                'qualifier' => $qualifier,
                'ts'        => $c->created_at,
                'when'      => null, // fill after sorting
                'note'      => implode(' ', $noteParts),
                'status'    => $statusText,
                'duration'  => $durationStr ?: '—',
            ]);
        }

        // Ticket items (unchanged)
        foreach ($tickets as $t) {
            $subject = trim((string) ($t->subject ?? 'Ticket'));
            $timeline->push([
                'type' => 'ticket',
                'ts'   => $t->created_at,
                'when' => null,
                'note' => "{$subject} — Opened",
            ]);
        }

        // Sort desc, format 'when', keep top 5 (unchanged)
        $timeline = $timeline
            ->sortByDesc('ts')
            ->values()
            ->take(5)
            ->map(function ($item) {
                $item['when'] = $item['ts']->diffForHumans();
                unset($item['ts']);
                return $item;
            })
            ->all();

        return response()->json(array_merge($response, ['timeline' => $timeline]), 200);
    }


 

    private function mapCallToHistoryItem($c, $orgId): array
    {
        $fromNumber = $this->normalizePhone((string) optional($c)->caller_id);
        $toNumber   = $this->normalizePhone((string) optional($c)->destination);

        $counterparty = (int) optional($c)->uas === 1
            ? ($fromNumber ?: $toNumber)
            : ($toNumber   ?: $fromNumber);

        $who = $this->displayNameForNumber($orgId, $counterparty) ?? ($counterparty ?: 'Unknown');

        $rawDuration = $c->duration;
        $durationStr = function_exists('duration_format') ? duration_format($rawDuration) : (string) $rawDuration;

        $statusEnum = $this->normalizeCallStatus(optional($c->bridgeCall)->status ?? $c->status ?? null);
        $statusText = $statusEnum ? $statusEnum->getText() : '—';
        $statusCss  = $statusEnum ? $statusEnum->getCss()  : '';

        $dirText    = (int) optional($c)->uas === 1 ? 'Incoming' : 'Outgoing';
        $qualifier  = $this->qualifierFromStatus($statusEnum, $rawDuration);
        if ($qualifier) $qualifier = "{$qualifier}";

        return [
            'id'        => $c->id,
            'when'      => $c->created_at ? $c->created_at->diffForHumans() : '—',
            'direction' => $dirText,
            'who'       => $who,
            'status'    => $statusText,
            'statusCss' => $statusCss,
            'qualifier' => $qualifier,
            'duration'  => $durationStr ?: '—',
        ];
    }


    private function initialsFromName(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') return null;

        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
        if (!$parts || count($parts) === 0) return null;

        if (count($parts) === 1) {
            return Str::upper(mb_substr($parts[0], 0, 2));
        }
        return Str::upper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
    }


   


    private function displayNameForNumber(int|string $orgId, ?string $phone): ?string
    {
        static $memo = [];
        $phone = $this->normalizePhone((string) $phone);

        if ($phone === '') return null;
        if (isset($memo[$phone])) return $memo[$phone];

        $lead = Lead::where('organization_id', $orgId)->where('phone', $phone)->first();
        if ($lead && !empty($lead->name)) {
            return $memo[$phone] = trim($lead->name) . " ({$phone})";
        }

        $contact = Contact::where('organization_id', $orgId)->where('tel_no', $phone)->first();
        if ($contact) {
            $full = trim(($contact->first_name ?? '') . ' ' . ($contact->last_name ?? ''));
            if ($full !== '') {
                return $memo[$phone] = "{$full} ({$phone})";
            }
        }

        return $memo[$phone] = $phone;
    }


    private function normalizeCallStatus($raw): ?CallStatusEnum
    {
        if ($raw instanceof CallStatusEnum) return $raw;
        if (is_numeric($raw)) return CallStatusEnum::fromKey((int) $raw);
        if (is_string($raw) && is_numeric($raw)) return CallStatusEnum::fromKey((int) $raw); // kept for parity
        return null;
    }


    private function qualifierFromStatus(?CallStatusEnum $status, $rawDuration): ?string
    {
        $dur = is_numeric($rawDuration) ? (int) $rawDuration : null;

        return match ($status) {
            CallStatusEnum::Busy,
            CallStatusEnum::NoAnswer,
            CallStatusEnum::Cancelled,
            CallStatusEnum::Failed        => 'Missed',
            CallStatusEnum::Disconnected  => ($dur !== null && $dur > 0) ? 'Answered' : 'Missed',
            CallStatusEnum::Dialing,
            CallStatusEnum::Ringing       => 'Ringing',
            CallStatusEnum::Established   => ($dur !== null && $dur > 0) ? 'Answered' : null,
            CallStatusEnum::Queued        => 'Queued',
            default                       => ($dur !== null && $dur > 0) ? 'Answered' : null,
        };
    }

    
}
