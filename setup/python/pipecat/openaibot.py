"""Pipecat bot implementation backed by OpenAI services."""

from dataclasses import dataclass
from datetime import datetime
from typing import Any, Dict, List

from fastapi import WebSocket
from loguru import logger

from constants import (
    DEFAULT_AUDIO_SAMPLE_RATE,
    DEFAULT_AUDIO_STYLE,
    DEFAULT_AUDIO_STYLE_DEGREE,
    DEFAULT_AUDIO_STYLE_RATE,
    DEFAULT_AZURE_VOICE,
    DEFAULT_GREETING,
    DEFAULT_SYSTEM_PROMPT,
    env,
)
from nextgenswitch_serializer import NextGenSwitchFrameSerializer
from pipecat.audio.vad.silero import SileroVADAnalyzer
from pipecat.pipeline.pipeline import Pipeline
from pipecat.pipeline.runner import PipelineRunner
from pipecat.pipeline.task import PipelineParams, PipelineTask
from pipecat.processors.aggregators.openai_llm_context import OpenAILLMContext
from pipecat.processors.audio.audio_buffer_processor import AudioBufferProcessor
from pipecat.services.azure.tts import AzureTTSService
from pipecat.services.fal.stt import FalSTTService
from pipecat.services.openai.llm import OpenAILLMService
from pipecat.transcriptions.language import Language
from pipecat.transports.network.fastapi_websocket import (
    FastAPIWebsocketParams,
    FastAPIWebsocketTransport,
)

from record_handler import save_audio


@dataclass
class OpenAIBotConfig:
    api_key: str
    fal_key: str
    azure_key: str
    azure_region: str
    voice: str
    system_prompt: str
    greeting: str
    testing: bool = False

    @classmethod
    def from_payload(cls, params: Dict[str, Any], testing: bool) -> "OpenAIBotConfig":
        return cls(
            api_key=params.get("openai_api_key") or env("OPENAI_API_KEY", "") or "",
            fal_key=params.get("fal_key") or env("FAL_KEY", "") or "",
            azure_key=params.get("azure_api_key") or env("AZURE_SPEECH_API_KEY", "") or "",
            azure_region=params.get("azure_region") or env("AZURE_SPEECH_REGION", "") or "",
            voice=params.get("voice") or env("AZURE_SPEECH_VOICE", DEFAULT_AZURE_VOICE) or DEFAULT_AZURE_VOICE,
            system_prompt=params.get("prompt") or env("PIPECAT_OPENAI_PROMPT", DEFAULT_SYSTEM_PROMPT) or DEFAULT_SYSTEM_PROMPT,
            greeting=params.get("greetings") or env("PIPECAT_OPENAI_GREETING", DEFAULT_GREETING) or DEFAULT_GREETING,
            testing=testing,
        )

    def missing_credentials(self) -> List[str]:
        required = {
            "OPENAI_API_KEY": self.api_key,
            "FAL_KEY": self.fal_key,
            "AZURE_SPEECH_API_KEY": self.azure_key,
            "AZURE_SPEECH_REGION": self.azure_region,
        }
        return [name for name, value in required.items() if not value]

    def describe(self) -> None:
        logger.debug(
            "OpenAI bot configured voice={} testing={} missing_credentials={}",
            self.voice,
            self.testing,
            ", ".join(self.missing_credentials()) or "none",
        )


def _build_transport(websocket_client: WebSocket) -> FastAPIWebsocketTransport:
    return FastAPIWebsocketTransport(
        websocket=websocket_client,
        params=FastAPIWebsocketParams(
            audio_in_enabled=True,
            audio_out_enabled=True,
            add_wav_header=False,
            vad_analyzer=SileroVADAnalyzer(),
            serializer=NextGenSwitchFrameSerializer(),
        ),
    )


def _build_pipeline(
    transport: FastAPIWebsocketTransport,
    llm: OpenAILLMService,
    stt: FalSTTService,
    tts: AzureTTSService,
    context_aggregator,
    audiobuffer: AudioBufferProcessor,
) -> PipelineTask:
    pipeline = Pipeline(
        [
            transport.input(),
            stt,
            context_aggregator.user(),
            llm,
            tts,
            transport.output(),
            audiobuffer,
            context_aggregator.assistant(),
        ]
    )

    return PipelineTask(
        pipeline,
        params=PipelineParams(
            audio_in_sample_rate=DEFAULT_AUDIO_SAMPLE_RATE,
            audio_out_sample_rate=DEFAULT_AUDIO_SAMPLE_RATE,
            enable_metrics=True,
            enable_usage_metrics=True,
        ),
    )


async def run_bot(
    websocket_client: WebSocket,
    stream_sid: str,
    call_sid: str,
    bot_params: Dict[str, Any],
    testing: bool,
) -> None:
    config = OpenAIBotConfig.from_payload(bot_params or {}, testing)
    config.describe()

    transport = _build_transport(websocket_client)

    llm = OpenAILLMService(api_key=config.api_key)
    stt = FalSTTService(
        api_key=config.fal_key,
        params=FalSTTService.InputParams(language=Language.BN),
    )
    tts = AzureTTSService(
        api_key=config.azure_key,
        region=config.azure_region,
        voice=config.voice,
        params=AzureTTSService.InputParams(
            language=Language.BN_BD,
            rate=DEFAULT_AUDIO_STYLE_RATE,
            style=DEFAULT_AUDIO_STYLE,
            style_degree=DEFAULT_AUDIO_STYLE_DEGREE,
        ),
    )

    context_messages = [
        {
            "role": "system",
            "content": config.system_prompt,
        }
    ]
    context = OpenAILLMContext(context_messages)
    context_aggregator = llm.create_context_aggregator(context)

    audiobuffer = AudioBufferProcessor()
    task = _build_pipeline(transport, llm, stt, tts, context_aggregator, audiobuffer)

    @transport.event_handler("on_client_connected")
    async def on_client_connected(_, client) -> None:
        logger.info(
            "OpenAI client connected: stream={} call={} peer={} testing={}",
            stream_sid,
            call_sid,
            client,
            testing,
        )
        await audiobuffer.start_recording()
        context_messages.append({"role": "system", "content": config.greeting})
        await task.queue_frames([context_aggregator.user().get_context_frame()])

    @transport.event_handler("on_client_disconnected")
    async def on_client_disconnected(_, client) -> None:
        logger.info("OpenAI client disconnected: stream={} call={} peer={}", stream_sid, call_sid, client)
        await task.cancel()

    @audiobuffer.event_handler("on_audio_data")
    async def on_audio_data(_, audio: bytes, sample_rate: int, num_channels: int) -> None:
        timestamp = datetime.utcnow().strftime("%Y%m%d_%H%M%S_%f")
        filename = f"{call_sid}_{timestamp}"
        await save_audio(filename, audio, sample_rate, num_channels)

    runner = PipelineRunner(handle_sigint=False, force_gc=True)
    await runner.run(task)
