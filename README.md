# NextGenSwitch — SoftSwitch, Multi‑Tenant PBX, Call Center, CCaaS

[NextGenSwitch](https://nextgenswitch.com) is a programmable, API‑driven SIP SoftSwitch that unifies PBX, call center, call broadcasting, virtual voice bot, and contact center capabilities in a single scalable platform.

## Key Features

- Comprehensive communication suite (PBX, Call Center, CCaaS)
- PBX: extensions, call queues, ring groups, call parking
- IVR: multi‑level menus and voice announcements
- Contact center: campaigns, broadcasting, click‑to‑call
- AI voice bot tooling
- Helpdesk with ticketing
- CRM integration with click‑to‑call
- Programmable, developer‑friendly APIs with built‑in voice verbs
- Security, compliance, and horizontal scalability

![NextGenSwitch dashboard](https://nextgenswitch.com/nextgenswitch_dashboard.png)

## Demo Access

- URL: http://demo.nextgenswitch.com
- User: `demo@nextgenswitch.com`
- Password: `demopass`
- SIP Domain: `demo.nextgenswitch.com`
- SIP Ports: `5060` (UDP/TCP), `5061` (TLS)

## Install Options

- Download ISO: https://nextgenswitch.com/download/iso
- Packages: RPM/DEB for Red Hat–based and Ubuntu systems
- Full installation docs: https://nextgenswitch.com/docs/installation/

## Documentation

- Product docs: https://nextgenswitch.com/docs
- Programmable Voice API: https://nextgenswitch.com/docs/programmable-voice-api/

## Developer Quick Start (Voice Verbs)

Hello World response (Twilio‑style verbs):

```xml
<?xml version="1.0" encoding="UTF-8"?>
<Response>
  <Say>Hello, world!</Say>
</Response>
```

Collect DTMF input:

```xml
<Gather action="https://example.com/process_input" method="POST" maxDigits="4" timeout="10">
  <Say>Please enter your 4-digit PIN.</Say>
</Gather>
```

Dial out with optional audio:

```xml
<Dial to="+1234567890" answerOnBridge="true" record="record-from-answer">
  <Play>https://example.com/audio/connecting.mp3</Play>
</Dial>
```

Trigger a call via API:

```bash
curl \
  --header "X-Authorization: YOUR_AUTH_CODE" \
  --header "X-Authorization-Secret: YOUR_AUTH_SECRET" \
  --request POST \
  --data 'to=23123&from=2323&statusCallback=http://your_status_callback_url&response=http://your_xml_response_document_url' \
  http://NEXTGENSWITCH_URL/api/v1/call
```

## Notes

- Replace placeholders like `YOUR_AUTH_CODE` and `NEXTGENSWITCH_URL` with your actual values.
- See the full API reference for all supported verbs, parameters, and callbacks.

