<?php

return [
    'call_create_path'      => '/call/create',
    'worker_create_path'      => '/worker/create',
    'call_modify_path'      => '/call/modify',
    'channel_register_path' => '/sip_user/register',
    'websocket_send_path' => '/websocket/send',
    'set_license'          => '/license',
    'date_time_format' => 'd-m-Y H:i:s',
    'core_functions' =>[
        ['name' => 'SIPCALL', 'func_type' => 1, 'func' => 'sip_call'],
        ['name' => 'EXTENSION', 'func_type' => 0, 'func' => 'extension'],
        ['name' => 'SMS', 'func_type' => 0, 'func' => 'sms'],
        ['name' => 'ANNOUNCEMENT', 'func_type' => 0, 'func' => 'announcement'],
        ['name' => 'OUTBOUND ROUTE', 'func_type' => 1, 'func' => 'outbound_route'],
        ['name' => 'IVR', 'func_type' => 0, 'func' => 'ivr'],
        ['name' => 'CUSTOM FUNCTION', 'func_type' => 0, 'func' => 'custom_function'],
        ['name' => 'TERMINATE CALL', 'func_type' => 0, 'func' => 'terminate_call'],
        ['name' => 'VOICE RECORD', 'func_type' => 0, 'func' => 'voice_record'],
        ['name' => 'RING GROUP', 'func_type' => 0, 'func' => 'ring_group'],
        ['name' => 'CALL QUEUE', 'func_type' => 0, 'func' => 'call_queue'],
        ['name' => 'SHORT CODE', 'func_type' => 1, 'func' => 'short_code'],
        ['name' => 'TIME CONDITION', 'func_type' => 0, 'func' => 'time_condition'],
        ['name' => 'CALL SURVEY', 'func_type' => 0, 'func' => 'call_survey'],
        ['name' => 'CALL PARKING', 'func_type' => 0, 'func' => 'call_parking'],
        ['name' => 'QUEUE JOIN', 'func_type' => 1, 'func' => 'queue_join']
    ],
];