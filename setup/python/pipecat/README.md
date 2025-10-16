# Pipecat Bot Server

This directory hosts the FastAPI server and bot runners that connect Twilio audio streams to Pipecat-powered assistants (OpenAI and Gemini). Follow the steps below to set up a local virtual environment, install dependencies, configure credentials, and run the service.

## 1. Prerequisites

- Python 3.10 or later
- Access credentials for the services you plan to use (OpenAI, Google Gemini, Azure Speech, Fal STT, NextGenSwitch)

## 2. Create and Activate a Virtual Environment

```bash
cd setup/python/pipecat
python3 -m venv .venv
source .venv/bin/activate  # On Windows use: .venv\Scripts\activate
```

To leave the environment later, run `deactivate`.

## 3. Install Dependencies

```bash
pip install --upgrade pip
pip install -r requirements.txt
```

> **Note:** The `pipecat-ai` package bundles the core audio pipeline. Some services (e.g., Azure, Fal, Gemini) may require additional native dependencies—refer to their official documentation if installation raises platform-specific errors.

## 4. Configure Environment Variables

Copy the sample environment file (if provided) or create a new `.env` alongside the Python modules:

```bash
cp .env.example .env  # Adjust paths if a template is stored elsewhere
```

Populate `.env` with the keys consumed in the code (examples):

- `OPENAI_API_KEY`
- `FAL_KEY`
- `AZURE_SPEECH_API_KEY`, `AZURE_SPEECH_REGION`
- `GOOGLE_API_KEY`
- `NEXTGENSWITCH_URL`, `NEXTGENSWITCH_KEY`, `NEXTGENSWITCH_SECRET`, `NEXTGENSWITCH_FORWARD`
- Optional overrides documented in `constants.py` (e.g., `PIPECAT_*` settings)

When developing, you can tweak runtime behaviour via command-line flags or env vars:

```bash
export PIPECAT_PORT=8767
export PIPECAT_ALLOWED_ORIGINS=https://your-frontend.local
```

## 5. Run the Server

With the virtual environment active and variables set, launch the FastAPI server:

```bash
python main.py --host 0.0.0.0 --port 8767
```

The service exposes:

- `POST /` to serve the TwiML streaming template
- `WS /openai` for the OpenAI-driven assistant
- `WS /gemini` for the Gemini-driven assistant

## 6. Testing Tips

- Use `python3 -m compileall .` to confirm modules compile cleanly.
- Tail the logs to ensure audio recordings and transcripts are written to `records/` and `transcripts/` respectively.
- Validate your Twilio webhook points to the running server and the stream template path (`templates/stream.xml`) exists.

## 7. Keeping Dependencies Updated

When you add new imports, update `requirements.txt` accordingly. After upgrades, re-run your conversational flows to confirm speech, transcription, and transfer features still operate as expected.

---
If you run into installation issues with vendor SDKs, consult the official documentation for platform-specific prerequisites or reach out to the Pipecat community for support.
