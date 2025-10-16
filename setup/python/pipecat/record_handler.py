"""Utilities for persisting Pipecat audio recordings."""

import io
from pathlib import Path
from typing import Optional
import wave

import aiofiles
from loguru import logger

from constants import DEFAULT_RECORDS_DIR, env

RECORDS_DIR = Path(env("PIPECAT_RECORDS_DIR", DEFAULT_RECORDS_DIR))


async def save_audio(
    name: str,
    audio: bytes,
    sample_rate: int,
    num_channels: int,
    directory: Optional[Path] = None,
) -> Optional[Path]:
    """Persist mixed audio output to disk as a WAV file."""

    if not audio:
        logger.info("No audio data to save for {}", name)
        return None

    target_dir = Path(directory) if directory else RECORDS_DIR
    target_dir.mkdir(parents=True, exist_ok=True)
    file_path = target_dir / f"{name}.wav"

    with io.BytesIO() as buffer:
        with wave.open(buffer, "wb") as wav_file:
            wav_file.setsampwidth(2)
            wav_file.setnchannels(num_channels)
            wav_file.setframerate(sample_rate)
            wav_file.writeframes(audio)

        async with aiofiles.open(file_path, "wb") as outfile:
            await outfile.write(buffer.getvalue())

    logger.info("Merged audio saved to {}", file_path)
    return file_path
