"""Shared defaults and environment helpers for Pipecat bot scripts."""

import os
from functools import lru_cache
from typing import Optional

from dotenv import load_dotenv

load_dotenv(override=True)

DEFAULT_SYSTEM_PROMPT = (
    "You are a helpful AI voice assistant for Infosoftbd Solutions. "
    "Your output will be converted to audio so do not include special characters in your answers. "
    "Infosoftbd Solutions is a private software firm in Bangladesh. "
    "The company provides software development solutions focused on call center, PBX, and ecommerce platforms. "
    "Always stay positive and answer concisely. You can speak in both Bangla and English."
)

DEFAULT_GEMINI_SYSTEM_PROMPT = (
    "You are a helpful AI voice assistant for Infosoftbd Solutions. "
    "Your output will be converted to audio so do not include special characters in your answers. "
    "Infosoftbd Solutions is a private software firm in Bangladesh. "
    "The company provides software development solutions focused on call center, PBX, and ecommerce platforms. "
    "Always stay positive and answer concisely. You can speak in both Bangla and English. "
    "Please try to speak in Bangla unless the user talks in English."
)

DEFAULT_GREETING = "Greet Customer first and ask if he is intereted of any solution of us."

DEFAULT_RECORDS_DIR = "records"
DEFAULT_TRANSCRIPTS_DIR = "transcripts"
DEFAULT_GEMINI_VOICE = "Leda"
DEFAULT_AZURE_VOICE = "bn-BD-PradeepNeural"
DEFAULT_AUDIO_SAMPLE_RATE = 8000
DEFAULT_AUDIO_STYLE_RATE = "1.1"
DEFAULT_AUDIO_STYLE = "cheerful"
DEFAULT_AUDIO_STYLE_DEGREE = "1.5"
DEFAULT_TRANSFER_DELAY_SECONDS = 5.0


@lru_cache(maxsize=None)
def env(name: str, default: Optional[str] = None) -> Optional[str]:
    """Return an environment variable or a default when unset."""

    value = os.getenv(name)
    if value in (None, ""):
        return default
    return value


def env_bool(name: str, default: bool = False) -> bool:
    """Return a boolean environment flag with a fallback."""

    value = env(name)
    if value is None:
        return default
    return value.strip().lower() in {"1", "true", "yes", "on"}


__all__ = [
    "DEFAULT_SYSTEM_PROMPT",
    "DEFAULT_GEMINI_SYSTEM_PROMPT",
    "DEFAULT_GREETING",
    "DEFAULT_RECORDS_DIR",
    "DEFAULT_TRANSCRIPTS_DIR",
    "DEFAULT_GEMINI_VOICE",
    "DEFAULT_AZURE_VOICE",
    "DEFAULT_AUDIO_SAMPLE_RATE",
    "DEFAULT_AUDIO_STYLE_RATE",
    "DEFAULT_AUDIO_STYLE",
    "DEFAULT_AUDIO_STYLE_DEGREE",
    "DEFAULT_TRANSFER_DELAY_SECONDS",
    "env",
    "env_bool",
]
