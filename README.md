# NextGenSwitch: Open-Source SIP Softswitch, Multi-Tenant PBX and Contact Center

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20.svg)](https://laravel.com/)

**NextGenSwitch** is an open-source, API-driven SIP softswitch and business communications platform. It combines multi-tenant IP PBX, contact center, call broadcasting, IVR, programmable voice APIs, CRM-assisted calling, and AI voice-agent integration in one Laravel-based application.

- Website: [nextgenswitch.com](https://nextgenswitch.com/)
- Documentation: [nextgenswitch.com/docs](https://nextgenswitch.com/docs/)
- Installation guide: [nextgenswitch.com/docs/installation](https://nextgenswitch.com/docs/installation/)
- Pricing and deployment options: [Plans and pricing](https://nextgenswitch.com/plans-and-pricing/)
- Support: [Contact NextGenSwitch](https://nextgenswitch.com/contact/)

![NextGenSwitch multi-tenant PBX and contact center dashboard](https://nextgenswitch.com/nextgenswitch_dashboard.png)

## What NextGenSwitch Does

NextGenSwitch helps service providers, developers, call centers, and communications teams build and operate SIP-based voice workflows.

| Capability | Included workflows |
| --- | --- |
| SIP softswitch | SIP routing, extensions, trunks, inbound and outbound calling |
| Multi-tenant IP PBX | Extensions, ring groups, queues, call parking, tenant administration |
| Contact center | Campaigns, click-to-call, broadcasting, agent workflows |
| IVR and automation | Multi-level IVR, announcements, DTMF collection, callbacks |
| Programmable Voice API | XML voice verbs, call initiation, status callbacks, media playback |
| AI voice integration | WebSocket media streaming and external voice-assistant connections |
| Business integrations | Helpdesk, ticketing, CRM-assisted click-to-call |

## Why Use NextGenSwitch?

- Consolidate PBX, contact-center, and programmable-voice workflows.
- Build custom call flows with developer-friendly APIs and XML voice verbs.
- Connect SIP and PSTN communications to external AI voice services.
- Deploy on your infrastructure and integrate with existing business systems.
- Start with an installer, a DigitalOcean image, or documented package options.

Production suitability depends on your carrier, network, security controls, capacity planning, monitoring, backups, and deployment architecture. Validate these dependencies in a non-production environment before rollout.

## Installation

Review the [full installation guide](https://nextgenswitch.com/docs/installation/) before changing a server.

### Available options

- [Download the installation ISO](https://nextgenswitch.com/download/iso)
- Use RPM or DEB packages for supported Red Hat-based and Ubuntu environments
- [Deploy from the DigitalOcean Marketplace](https://marketplace.digitalocean.com/apps/nextgenswitch)
- Follow the repository guide at [public/docs/installation.md](public/docs/installation.md)

The application requires PHP 8.1 or later. System services and telephony dependencies vary by deployment method.

## Developer Quick Start

NextGenSwitch supports XML call-control responses for programmable voice workflows.

### Play text to a caller

```xml
<?xml version="1.0" encoding="UTF-8"?>
<Response>
  <Say>Hello from NextGenSwitch.</Say>
</Response>
```

### Collect DTMF input

```xml
<Response>
  <Gather action="https://example.com/process-input" method="POST" maxDigits="4" timeout="10">
    <Say>Please enter your four-digit PIN.</Say>
  </Gather>
</Response>
```

### Dial a destination

```xml
<Response>
  <Dial to="+1234567890" answerOnBridge="true" record="record-from-answer">
    <Play>https://example.com/audio/connecting.mp3</Play>
  </Dial>
</Response>
```

### Stream audio to an AI voice assistant

```xml
<Response>
  <Connect>
    <Stream url="wss://voice.example.com/session">
      <Parameter name="session_id" value="example-session" />
    </Stream>
  </Connect>
</Response>
```

Do not place API keys or other secrets inside client-visible call-control documents. Resolve credentials securely on the receiving service.

### Initiate a call through the API

```bash
curl --request POST "https://YOUR_NEXTGENSWITCH_HOST/api/v1/call" \
  --header "X-Authorization: YOUR_AUTH_CODE" \
  --header "X-Authorization-Secret: YOUR_AUTH_SECRET" \
  --data-urlencode "to=15551234567" \
  --data-urlencode "from=1000" \
  --data-urlencode "statusCallback=https://example.com/call-status" \
  --data-urlencode "response=https://example.com/call-flow.xml"
```

Keep authorization values in environment variables or a secrets manager. Use TLS endpoints for production integrations.

## Product Evaluation

Use the [NextGenSwitch live demo](https://nextgenswitch.com/live-demo/) to request or start an evaluation. Do not publish access credentials, customer data, phone lists, or other sensitive information in issues or discussions.

## Documentation

- [Product documentation](https://nextgenswitch.com/docs/)
- [Programmable Voice API](https://nextgenswitch.com/docs/programmable-voice-api/)
- [Repository installation guide](public/docs/installation.md)
- [Features overview](https://nextgenswitch.com/features/)
- [Use cases](https://nextgenswitch.com/use-cases/)

## Contributing and Security

- Read [CONTRIBUTING.md](CONTRIBUTING.md) before opening a pull request.
- Report security vulnerabilities privately using the process in [SECURITY.md](SECURITY.md).
- For product and deployment questions, use the [support contact page](https://nextgenswitch.com/contact/).

## License

NextGenSwitch is available under the [MIT License](LICENSE). The NextGenSwitch name and trademarks remain the property of Infosoftbd Solutions.

## Topics

SIP softswitch, open-source softswitch, multi-tenant PBX, IP PBX, VoIP platform, contact center software, call center software, CCaaS, programmable voice API, SIP trunking, IVR, voice bot, AI voice agent, call broadcasting, Laravel telephony.
