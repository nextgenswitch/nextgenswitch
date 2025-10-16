"""Pipecat bot implementation backed by Google Gemini."""

import asyncio
from dataclasses import dataclass
from datetime import datetime
from pathlib import Path
from typing import Any, Dict, Optional

from fastapi import WebSocket
from loguru import logger

from constants import (
    DEFAULT_AUDIO_SAMPLE_RATE,
    DEFAULT_GEMINI_SYSTEM_PROMPT,
    DEFAULT_GEMINI_VOICE,
    DEFAULT_GREETING,
    DEFAULT_TRANSCRIPTS_DIR,
    env,
)
from nextgenswitch_serializer import NextGenSwitchFrameSerializer
from pipecat.adapters.schemas.tools_schema import ToolsSchema
from pipecat.audio.vad.silero import SileroVADAnalyzer
from pipecat.pipeline.pipeline import Pipeline
from pipecat.pipeline.runner import PipelineRunner
from pipecat.pipeline.task import PipelineParams, PipelineTask
from pipecat.processors.aggregators.openai_llm_context import OpenAILLMContext
from pipecat.processors.audio.audio_buffer_processor import AudioBufferProcessor
from pipecat.processors.transcript_processor import TranscriptProcessor
from pipecat.services.gemini_multimodal_live import GeminiMultimodalLiveLLMService
from pipecat.services.llm_service import FunctionCallParams
from pipecat.transports.network.fastapi_websocket import (
    FastAPIWebsocketParams,
    FastAPIWebsocketTransport,
)
from record_handler import save_audio
from transcript_handler import TranscriptHandler
from transfer_call import transfer_call


@dataclass
class GeminiBotConfig:
    google_api_key: str
    voice_id: str
    system_prompt: str
    greeting: str
    forwarding_number: Optional[str]
    transcripts_dir: Path
    testing: bool = False

    @classmethod
    def from_env(cls, params: Dict[str, Any], testing: bool) -> "GeminiBotConfig":
        return cls(
            google_api_key=params.get("google_api_key") or env("GOOGLE_API_KEY", "") or "",
            voice_id=params.get("voice_id") or env("PIPECAT_GEMINI_VOICE", DEFAULT_GEMINI_VOICE) or DEFAULT_GEMINI_VOICE,
            system_prompt=params.get("prompt") or env("PIPECAT_GEMINI_PROMPT", DEFAULT_GEMINI_SYSTEM_PROMPT) or DEFAULT_GEMINI_SYSTEM_PROMPT,
            greeting=params.get("greetings") or env("PIPECAT_GEMINI_GREETING", DEFAULT_GREETING) or DEFAULT_GREETING,
            forwarding_number=params.get("forwarding_number") or env("NEXTGENSWITCH_FORWARD"),
            transcripts_dir=Path(env("PIPECAT_TRANSCRIPTS_DIR", DEFAULT_TRANSCRIPTS_DIR)),
            testing=testing,
        )

    def transcript_path(self, call_sid: str) -> Path:
        self.transcripts_dir.mkdir(parents=True, exist_ok=True)
        return self.transcripts_dir / f"{call_sid}.json"

    def describe(self) -> None:
        logger.debug(
            "Gemini bot configured voice={} forwarding={} testing={}",
            self.voice_id,
            self.forwarding_number or "unset",
            self.testing,
        )


async def run_bot(
    websocket_client: WebSocket,
    stream_sid: str,
    call_sid: str,
    bot_params: Dict[str, Any],
    testing: bool,
) -> None:
    config = GeminiBotConfig.from_env(bot_params, testing)
    config.describe()

    transport = FastAPIWebsocketTransport(
        websocket=websocket_client,
        params=FastAPIWebsocketParams(
            audio_in_enabled=True,
            audio_out_enabled=True,
            add_wav_header=False,
            vad_analyzer=SileroVADAnalyzer(),
            serializer=NextGenSwitchFrameSerializer(),
        ),
    )

    async def transfer_call_into_live_agent(params: FunctionCallParams) -> None:
        if not config.forwarding_number:
            logger.error("Forwarding number is not configured; skipping live agent transfer for {}", call_sid)
            await params.result_callback({"status": "unsupported"})
            return

        logger.info("Transferring call {} to live agent {}", call_sid, config.forwarding_number)
        asyncio.create_task(transfer_call(call_sid, config.forwarding_number))
        await params.result_callback({"status": "transferred"})

    tools = ToolsSchema(standard_tools=[transfer_call_into_live_agent])

    llm = GeminiMultimodalLiveLLMService(
        api_key=config.google_api_key,
        voice_id=config.voice_id,
        transcribe_user_audio=True,
        transcribe_model_audio=True,
        system_instruction=config.system_prompt,
        tools=tools,
    )

    llm.register_direct_function(transfer_call_into_live_agent, cancel_on_interruption=False)

    context = OpenAILLMContext(
        [
            {
                "role": "user",
                "content": config.greeting,
            }
        ],
        tools=tools,
    )
    context_aggregator = llm.create_context_aggregator(context)

    audiobuffer = AudioBufferProcessor()
    transcript_processor = TranscriptProcessor()
    transcript_handler = TranscriptHandler(output_file=str(config.transcript_path(call_sid)))

    pipeline = Pipeline(
        [
            transport.input(),
            context_aggregator.user(),
            transcript_processor.user(),
            llm,
            transport.output(),
            audiobuffer,
            transcript_processor.assistant(),
            context_aggregator.assistant(),
        ]
    )

    task = PipelineTask(
        pipeline,
        params=PipelineParams(
            audio_in_sample_rate=DEFAULT_AUDIO_SAMPLE_RATE,
            audio_out_sample_rate=DEFAULT_AUDIO_SAMPLE_RATE,
            enable_metrics=True,
            enable_usage_metrics=True,
        ),
    )

    @transport.event_handler("on_client_connected")
    async def on_client_connected(_, client) -> None:
        logger.info(
            "Gemini client connected: stream={} call={} peer={} testing={}",
            stream_sid,
            call_sid,
            client,
            testing,
        )
        await audiobuffer.start_recording()
        await task.queue_frames([context_aggregator.user().get_context_frame()])

    @transport.event_handler("on_client_disconnected")
    async def on_client_disconnected(_, client) -> None:
        logger.info("Gemini client disconnected: stream={} call={} peer={}", stream_sid, call_sid, client)
        await task.cancel()

    @transcript_processor.event_handler("on_transcript_update")
    async def on_transcript_update(processor, frame) -> None:
        await transcript_handler.on_transcript_update(processor, frame)

    @audiobuffer.event_handler("on_audio_data")
    async def on_audio_data(_, audio: bytes, sample_rate: int, num_channels: int) -> None:
        timestamp = datetime.utcnow().strftime("%Y%m%d_%H%M%S_%f")
        filename = f"{call_sid}_{timestamp}"
        await save_audio(filename, audio, sample_rate, num_channels)

    runner = PipelineRunner(handle_sigint=False, force_gc=True)
    await runner.run(task)
