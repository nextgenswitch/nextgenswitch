"""Helpers for capturing and persisting Pipecat transcripts."""

import asyncio
import json
from dataclasses import dataclass, field
from pathlib import Path
from typing import List, Optional

from loguru import logger
from pipecat.frames.frames import TranscriptionMessage, TranscriptionUpdateFrame
from pipecat.processors.transcript_processor import TranscriptProcessor

from constants import env


@dataclass
class TranscriptHandler:
    """Collects transcript messages and optionally persists them to disk."""

    output_file: Optional[str] = None
    messages: List[TranscriptionMessage] = field(default_factory=list, init=False)

    def __post_init__(self) -> None:
        configured_path = self.output_file or env("PIPECAT_TRANSCRIPT_FILE")
        self._output_path = Path(configured_path) if configured_path else None
        if self._output_path:
            self._output_path.parent.mkdir(parents=True, exist_ok=True)
        logger.debug("TranscriptHandler initialised with output_path={}", self._output_path)

    async def _persist_messages(self) -> None:
        if not self._output_path:
            return

        payload = [
            {
                "timestamp": message.timestamp,
                "role": message.role,
                "content": message.content,
            }
            for message in self.messages
        ]

        serialised = json.dumps(payload, ensure_ascii=False, indent=2)

        def write_payload() -> None:
            self._output_path.write_text(serialised, encoding="utf-8")

        await asyncio.to_thread(write_payload)

    async def save_message(self, message: TranscriptionMessage) -> None:
        record = {
            "timestamp": message.timestamp,
            "role": message.role,
            "content": message.content,
        }
        logger.info("Transcript: {}", record)
        self.messages.append(message)
        await self._persist_messages()

    async def on_transcript_update(
        self, processor: TranscriptProcessor, frame: TranscriptionUpdateFrame
    ) -> None:
        logger.debug("Received transcript update with {} new messages", len(frame.messages))
        for message in frame.messages:
            await self.save_message(message)
