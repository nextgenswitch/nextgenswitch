# Next Generation SoftSwitch, Multi Tenant PBX, Call Center and Contact Center (CCPAAS) Solution

[NextGenSwitch](https://nextgenswitch.com) is a cutting-edge, programmable API-driven SIP SoftSwitch designed to revolutionize voice communication solutions for businesses of all sizes. This versatile platform integrates a comprehensive suite of communication tools, including PBX, Call Center , Call Broadcasting, Virtual Voice Bot and Contact center Solution into a unified and scalable platform.Let’s dive into why businesses should consider choosing NextGenSwitch for their communication needs. Here’s a compelling list of reasons highlighting its unique features, benefits, and advantages:

- Comprehensive Communication Suite.
- PBX features like extension, Call Queue , Ring Group , Call parking.
- IVR features with mulit level and voice anouncements.
- Contact Center feature like Campaign , Broadcast and click to call.
- AI Voice bot development features .
- Helpdesk with ticket management
- CRM integraton available with click to call facility
- Programmable API-Driven Architecture.
- Scalability and Growth.
- Developer-Friendly Platform.
- Built-in Voice Response Verbs.
- Security and Compliance.

Multi-Tenant Virtual PBX , AI BOT, Call Center , Campaign and Survey solution.
## Developer Friendly platform (alternative to Twilio Platform)
[NextGenSwitch](https://nextgenswitch.com) support built in response verbs same as Twilio to send and recieve calls.
The following will say Hello World when the call will be established.
```
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say>Hello, world!</Say>
</Response>
```
To gather DTMF or voice
```
<Gather action="https://example.com/process_input" method="POST" maxDigits="4" timeout="10">
    <Say>Please enter your 4-digit PIN. </Say>
</Gather>
```

To dial a new number
```
 <Dial to="+1234567890" answerOnBridge="true" record="record-from-answer">
        <Play>https://example.com/audio/connecting.mp3</Play>
 </Dial>
```


A raw curl request given below
```
curl --header "X-Authorization: Your_authorizaton_code" \
  --header "X-Authorization-Secre: Your_authorizaton_secret" \
  --request POST \
  --data 'to=23123&from=2323&statusCallback=http://your_status_callback_url&response=http://your_xml_response_document_url' \
  http://nextgenswitch_url/api/v1/call
```
A full documentation can be found on https://nextgenswitch.com/docs/programmable-voice-api/

## Demo access
> URL: http://demo.nextgenswitch.com \
> user: demo@nextgenswitch.com \
> pass: demopass \
> SIP  Domain: demo.nextgenswitch.com \
> SIP Port: 5060 (UDP + TCP) , 5061 (TLS)

Documentation can found on  [NextGenSwitch Docs](https://nextgenswitch.com/docs).\
For plan and pricing  [NextGenSwitch Pricing](https://nextgenswitch.com/plans-and-pricing/).

