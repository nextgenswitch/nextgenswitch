# Install NextGenSwitch

This repository no longer distributes the current application source. Use one of the supported deployment paths below.

## 1. Choose a Deployment Path

### DigitalOcean Marketplace

Use the [NextGenSwitch DigitalOcean Marketplace image](https://marketplace.digitalocean.com/apps/nextgenswitch) for a guided cloud deployment.

1. Open the Marketplace listing.
2. Select a suitable Droplet region and size for the expected concurrent-call and agent workload.
3. Configure authentication, networking, firewall rules, DNS, and storage.
4. Deploy the image.
5. Complete the NextGenSwitch application setup and validate SIP connectivity before production use.

### Free Pilot Instance

Use the [free instance application](https://nextgenswitch.com/apply-for-free/) for testing, demonstrations, and small pilots. The website currently describes the Community/Pilot option as supporting one agent, up to ten extensions, core PBX with inbound queues, API access, and email or ticket support.

Do not submit secrets, production customer data, real calling lists, or call recordings during an evaluation.

### Assisted or Self-Hosted Deployment

For AWS, Azure, DigitalOcean, on-premises, or another VPS provider, [contact NextGenSwitch](https://nextgenswitch.com/contact/) to confirm the supported software package, licensing, infrastructure sizing, SIP requirements, and deployment process.

## 2. Review Requirements

Before deployment, confirm:

- Target Linux distribution and supported installation package
- Root or sudo access
- Public DNS and TLS certificates
- Firewall policy and required web, SIP, RTP, and management access
- SIP trunk or PSTN carrier connectivity
- Database, cache, storage, and backup design
- Expected agents, extensions, tenants, concurrent calls, campaigns, and recordings
- Monitoring, alerting, logging, incident response, and maintenance ownership
- Required AI, speech, messaging, or CRM provider accounts

The [official installation documentation](https://nextgenswitch.com/docs/installation/) describes the Linux installer workflow and its PHP, Apache, Supervisor, database, filesystem, and runtime dependencies. Obtain the supported installation assets before following source-based installer commands.

## 3. Secure the Deployment

- Replace all default or temporary credentials.
- Store API, SIP, database, and provider secrets outside source-controlled files.
- Use TLS for web, API, WebSocket, and supported SIP connections.
- Restrict management access by network and role.
- Apply operating-system and application security updates.
- Protect call recordings, messages, logs, and personal data according to applicable requirements.
- Configure rate limits, fraud controls, carrier limits, and spending alerts.

## 4. Validate Before Production

Test:

- Inbound and outbound calls
- SIP registration and trunk failover
- IVR, queues, ring groups, transfers, and voicemail
- Campaign pacing, retry rules, time zones, and opt-out handling
- Recording permissions, storage, retention, and retrieval
- API authentication, callbacks, XML voice verbs, and WebSocket streaming
- AI provider timeout, error, and human-handoff behavior
- Message-provider delivery and live-agent escalation
- Monitoring, backups, restore procedures, and incident contacts

## 5. Get Help

- [Product documentation](https://nextgenswitch.com/docs/)
- [Features](https://nextgenswitch.com/features/)
- [Plans and pricing](https://nextgenswitch.com/plans-and-pricing/)
- [Contact and support](https://nextgenswitch.com/contact/)
- [Live demo](https://nextgenswitch.com/live-demo/)
