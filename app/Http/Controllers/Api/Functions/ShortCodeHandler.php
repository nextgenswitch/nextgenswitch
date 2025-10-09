<?php

namespace App\Http\Controllers\Api\Functions;

use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Api\VoiceResponse;
use App\Models\Call;
use App\Models\CallQueue;
use App\Models\CallQueueExtension;
use App\Models\Extension;

class ShortCodeHandler
{
    public function __construct(private FunctionCall $functionCall)
    {
    }

    public function handle(string $code)
    {
        info('method is ' . request()->method());
        $data = $this->functionCall->getRequestData();
        info('Request data:');
        info($data);

        info('Received code from request ' . $code);

        $parsedCode = $this->parseShortCode($code);

        info('extracted short code ' . $parsedCode['code']);

        if (isset($data['gather_for_set_forward']) && (int) $data['gather_for_set_forward'] === 1) {
            return $this->handleForwardSet($data, $code);
        }

        if (isset($data['gather_for_dynamic_queue'])) {
            $newCode = null;

            if (!empty($data['digits'])) {
                $gatherMode = (int) $data['gather_for_dynamic_queue'];

                if ($gatherMode === 1) {
                    $newCode = sprintf('%s*%s', $parsedCode['code'], $data['digits']);
                } elseif ($gatherMode === 2) {
                    $params = $parsedCode['params'];
                    $firstParam = $params[0] ?? '';
                    $newCode = sprintf('%s*%s*%s', $parsedCode['code'], $firstParam, $data['digits']);
                }
            }

            if ($newCode) {
                return $this->handleDynamicQueue($newCode, $data);
            }
        }

        $shortCodes = config('easypbx.short_codes');
        $key = array_search($parsedCode['code'], $shortCodes);

        if ($key === false) {
            return $this->playNotFound();
        }

        return match ($key) {
            'always_forward_activate' => $this->handleForwardActivate($data),
            'forward_when_busy' => $this->handleForwardActivate($data, 2),
            'forward_when_no_answer' => $this->handleForwardActivate($data, 3),
            'forward_when_unavailable' => $this->handleForwardActivate($data, 4),
            'forward_deativate' => $this->handleForwardDeactivate($data),
            'forward_set' => $this->handleForwardSet($data, $code),
            'enable_disabled_dynamic_queue' => $this->handleDynamicQueue($code, $data),
            default => $this->playNotFound(),
        };
    }

    private function parseShortCode(string $input): array
    {
        $decoded = urldecode($input);
        $trimmed = trim($decoded, '*#');
        $parts = explode('*', $trimmed);

        $shortcode = array_shift($parts); // first part is shortcode

        return [
            'code' => sprintf('*%s', $shortcode),
            'params' => $parts,
        ];
    }

    private function handleForwardActivate(array $data, int $code = 0)
    {
        info('exicute forward active function');

        if (!isset($data['channel_id'])) {
            info('Could not find channel id to activate forward');
            return $this->response();
        }

        Extension::where('function_id', 1)
            ->where('destination_id', $data['channel_id'])
            ->update(['forwarding' => $code]);

        $response = $this->response();
        $response->play(storage_path('app/public/sounds/call_forwarding_activated.wav'), ['localfile' => 'true']);

        return $response;
    }

    private function handleForwardDeactivate(array $data)
    {
        info('exicute forward deactive function');

        if (!isset($data['channel_id'])) {
            info('Could not find channel id to deactivate forward');
            return $this->response();
        }

        Extension::where('function_id', 1)
            ->where('destination_id', $data['channel_id'])
            ->update(['forwarding' => 1]);

        $response = $this->response();
        $response->play(storage_path('app/public/sounds/call_forwarding_deactivated.wav'), ['localfile' => 'true']);

        return $response;
    }

    private function handleForwardSet(array $data, string $code)
    {
        $parsedCode = $this->parseShortCode($code);
        $params = $parsedCode['params'];
        $number = count($params) ? $params[0] : null;
        info('extracted number ' . $number);

        if ($number) {
            $extension = Extension::where('function_id', 1)
                ->where('destination_id', $data['channel_id'] ?? null)
                ->first();

            if ($extension) {
                return $this->setForwardingNumber($extension, $number);
            }
        }

        if (!empty($data['digits'])) {
            $call = Call::find($data['call_id'] ?? null);
            if ($call) {
                $extension = Extension::where([
                    'destination_id' => $call->sip_user_id,
                    'extension_type' => 1,
                ])->first();

                if ($extension) {
                    return $this->setForwardingNumber($extension, $data['digits']);
                }
            }
        }

        $funcId = $this->functionCall->getFuncId();
        $response = $this->response();
        $gather = $response->gather([
            'finishOnKey' => '#',
            'action' => route('api.func_call', [
                'func_id' => $funcId,
                'dest_id' => $code,
                'gather_for_set_forward' => 1,
            ]),
        ]);

        $gather->play(storage_path('app/public/sounds/enter_forwarding_number.wav'), ['localfile' => 'true']);

        $response->say("You didn't enter your forwarding number.");
        $response->redirect(route('api.func_call', [
            'func_id' => $funcId,
            'dest_id' => $code,
        ]));

        return $response;
    }

    private function handleDynamicQueue(string $code, array $data)
    {
        info('Dynamic Queue block exicuted');
        info('code' . $code);
        info('Data');
        info($data);

        $parsedCode = $this->parseShortCode($code);
        $params = $parsedCode['params'];

        info('params');
        info($params);

        $response = $this->response();
        $funcId = $this->functionCall->getFuncId();

        if (count($params) >= 2) {
            $extension = Extension::where('function_id', 1)
                ->where('destination_id', $data['channel_id'] ?? null)
                ->first();

            if ($extension) {
                $queueExtension = Extension::where('organization_id', $extension->organization_id)
                    ->where('code', $params[0])
                    ->first();

                if ($queueExtension) {
                    $callQueue = CallQueue::where('extension_id', $queueExtension->id)->first();

                    if ($callQueue) {
                        $callQExtension = CallQueueExtension::where('call_queue_id', $callQueue->id)
                            ->where('extension_id', $extension->id)
                            ->first();

                        if ($callQExtension) {
                            $dynamicQueue = $params[1] == 1 ? 1 : 0;
                            $callQExtension->update(['dynamic_queue' => $dynamicQueue]);
                            $response->play(storage_path('app/public/sounds/dynamic_queue_updated.wav'), ['localfile' => true]);
                            return $response;
                        }

                        $response->play(storage_path('app/public/sounds/dynamic_queue_extension_not_exists.wav'), ['localfile' => true]);
                        return $response;
                    }
                }
            }

            $response->play(storage_path('app/public/sounds/queue_not_exists.wav'), ['localfile' => true]);
            return $response;
        }

        if (count($params) >= 1) {
            $gather = $response->gather([
                'numDigits' => '1',
                'action' => route('api.func_call', [
                    'func_id' => $funcId,
                    'dest_id' => $code,
                    'gather_for_dynamic_queue' => 2,
                    'channel_id' => $data['channel_id'] ?? null,
                ]),
            ]);

            $gather->play(storage_path('app/public/sounds/dynamic_queue_enable_disable_input.wav'), ['localfile' => 'true']);

            $response->say("You didn't press any key.");
            $response->redirect(route('api.func_call', [
                'func_id' => $funcId,
                'dest_id' => $code,
            ]));

            return $response;
        }

        $gather = $response->gather([
            'finishOnKey' => '#',
            'action' => route('api.func_call', [
                'func_id' => $funcId,
                'dest_id' => $code,
                'gather_for_dynamic_queue' => 1,
                'channel_id' => $data['channel_id'] ?? null,
            ]),
        ]);

        $gather->play(storage_path('app/public/sounds/enter_queue_code.wav'), ['localfile' => 'true']);

        $response->say("You didn't enter your queue code.");
        $response->redirect(route('api.func_call', [
            'func_id' => $funcId,
            'dest_id' => $code,
        ]));

        return $response;
    }

    private function setForwardingNumber(Extension $extension, string $number)
    {
        $extension->update(['forwarding_number' => $number]);
        $response = $this->response();
        $response->play(storage_path('app/public/sounds/call_forwarding_number_set.wav'), ['localfile' => 'true']);

        return $response;
    }

    private function playNotFound()
    {
        $response = $this->response();
        $response->play(storage_path('app/public/sounds/short_code_not_exist.wav'), ['localfile' => true]);

        return $response;
    }

    private function response(): VoiceResponse
    {
        return $this->functionCall->getResponse();
    }
}
