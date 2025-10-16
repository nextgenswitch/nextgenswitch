"""Entry point for the Pipecat FastAPI server."""

import argparse
import json
import os
from pathlib import Path
from typing import Any, Dict, Optional

import uvicorn
from fastapi import FastAPI, HTTPException, WebSocket, WebSocketDisconnect
from fastapi.middleware.cors import CORSMiddleware
from loguru import logger
from starlette.responses import HTMLResponse

from geminibot import run_bot as gemini_bot
from openaibot import run_bot as openai_bot

BASE_DIR = Path(__file__).resolve().parent
TEMPLATE_DIR = BASE_DIR / "templates"
DEFAULT_TWIML_TEMPLATE = os.getenv("PIPECAT_TWIML_TEMPLATE", "stream.xml")
DEFAULT_HOST = os.getenv("PIPECAT_HOST", "0.0.0.0")
DEFAULT_PORT = int(os.getenv("PIPECAT_PORT", "8767"))
DEFAULT_STREAM_SID = os.getenv("PIPECAT_DEFAULT_STREAM_SID", "pipecat-test-stream")
DEFAULT_CALL_SID = os.getenv("PIPECAT_DEFAULT_CALL_SID", "pipecat-test-call")


def _allowed_origins() -> list[str]:
    raw_origins = os.getenv("PIPECAT_ALLOWED_ORIGINS", "*").strip()
    if raw_origins == "*":
        return ["*"]
    return [origin.strip() for origin in raw_origins.split(",") if origin.strip()]


def _extract_nested(data: Dict[str, Any], *keys: str) -> Optional[Any]:
    current: Any = data
    for key in keys:
        if not isinstance(current, dict):
            return None
        current = current.get(key)
        if current is None:
            return None
    return current


def load_twiml(template_name: str = DEFAULT_TWIML_TEMPLATE) -> str:
    template_path = TEMPLATE_DIR / template_name
    try:
        return template_path.read_text(encoding="utf-8")
    except FileNotFoundError as exc:
        logger.error("Missing TwiML template: {}", template_path)
        raise HTTPException(status_code=500, detail="TwiML template not found") from exc


async def _initialize_websocket(websocket: WebSocket) -> Dict[str, Any]:
    await websocket.accept()
    start_iterator = websocket.iter_text()

    try:
        start_message = await start_iterator.__anext__()
        logger.debug("Received websocket start frame: {}", start_message)
        raw_payload = await start_iterator.__anext__()
    except StopAsyncIteration as exc:
        logger.warning("Websocket disconnected before sending call data")
        raise WebSocketDisconnect(code=1002) from exc

    try:
        call_data = json.loads(raw_payload)
    except json.JSONDecodeError as exc:
        logger.error("Invalid JSON payload from websocket: {}", raw_payload)
        await websocket.close(code=1003)
        raise WebSocketDisconnect(code=1003) from exc

    return call_data


def create_app(testing: bool = False) -> FastAPI:
    app = FastAPI()
    app.state.testing = testing

    app.add_middleware(
        CORSMiddleware,
        allow_origins=_allowed_origins(),
        allow_credentials=True,
        allow_methods=["*"],
        allow_headers=["*"],
    )

    @app.post("/")
    async def start_call() -> HTMLResponse:
        logger.info("Serving TwiML template")
        twiml = load_twiml()
        return HTMLResponse(content=twiml, media_type="application/xml")

    @app.websocket("/openai")
    async def openai_websocket(websocket: WebSocket) -> None:
        try:
            call_data = await _initialize_websocket(websocket)
        except WebSocketDisconnect:
            logger.warning("OpenAI websocket initialization failed")
            raise

        stream_sid = (
            _extract_nested(call_data, "start", "streamSid")
            or call_data.get("streamSid")
            or DEFAULT_STREAM_SID
        )
        call_sid = call_data.get("callSid") or call_data.get("call_id") or DEFAULT_CALL_SID

        bot_params = call_data.get("params") or {}
        if not isinstance(bot_params, dict):
            logger.warning("OpenAI params payload must be a dict. Received {}", bot_params)
            bot_params = {}

        logger.info(
            "OpenAI websocket connected stream={} call={} testing={} params_keys={}",
            stream_sid,
            call_sid,
            app.state.testing,
            list(bot_params.keys()),
        )

        await openai_bot(websocket, stream_sid, call_sid, bot_params, app.state.testing)

    @app.websocket("/gemini")
    async def gemini_websocket(websocket: WebSocket) -> None:
        try:
            call_data = await _initialize_websocket(websocket)
        except WebSocketDisconnect:
            logger.warning("Gemini websocket initialization failed")
            raise

        stream_id = call_data.get("streamId") or call_data.get("streamSid") or DEFAULT_STREAM_SID
        call_sid = call_data.get("call_id") or call_data.get("callSid") or DEFAULT_CALL_SID

        bot_params = call_data.get("params") or {}
        if not isinstance(bot_params, dict):
            logger.warning("Gemini params payload must be a dict. Received {}", bot_params)
            bot_params = {}

        logger.info(
            "Gemini websocket connected stream={} call={} testing={} params_keys={}",
            stream_id,
            call_sid,
            app.state.testing,
            list(bot_params.keys()),
        )

        await gemini_bot(websocket, stream_id, call_sid, bot_params, app.state.testing)

    return app


app = create_app()


def _parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Pipecat Twilio Chatbot Server")
    parser.add_argument(
        "-t",
        "--test",
        action="store_true",
        default=False,
        help="run the server in testing mode",
    )
    parser.add_argument(
        "--host",
        default=DEFAULT_HOST,
        help="host interface to bind",
    )
    parser.add_argument(
        "--port",
        type=int,
        default=DEFAULT_PORT,
        help="port to bind",
    )
    return parser.parse_args()


if __name__ == "__main__":
    cli_args = _parse_args()
    uvicorn.run(create_app(testing=cli_args.test), host=cli_args.host, port=cli_args.port)
