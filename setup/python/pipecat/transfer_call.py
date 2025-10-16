"""Utilities for transferring an active call to a live agent."""

import asyncio
from datetime import datetime
from typing import Optional

import requests
from loguru import logger

from constants import DEFAULT_TRANSFER_DELAY_SECONDS, env

XML_TEMPLATE = """<?xml version="1.0"?>\n<response>\n    <dial>{number}</dial>\n</response>"""


async def transfer_call(call_sid: str, dial_number: str, delay: Optional[float] = None) -> None:
    """Transfer an active call to the supplied number."""

    base_url = env("NEXTGENSWITCH_URL")
    api_key = env("NEXTGENSWITCH_KEY")
    api_secret = env("NEXTGENSWITCH_SECRET")

    if not base_url:
        logger.error("NEXTGENSWITCH_URL is not configured; unable to transfer call {}", call_sid)
        return

    transfer_delay = DEFAULT_TRANSFER_DELAY_SECONDS if delay is None else max(delay, 0)
    if transfer_delay:
        logger.debug("Waiting {} seconds before transferring call {}", transfer_delay, call_sid)
        await asyncio.sleep(transfer_delay)

    url = f"{base_url.rstrip('/')}/{call_sid}"
    headers = {}
    if api_key:
        headers["X-Authorization"] = api_key
    if api_secret:
        headers["X-Authorization-Secret"] = api_secret

    payload = {"responseXml": XML_TEMPLATE.format(number=dial_number)}
    logger.info("Transferring call {} to {} via {}", call_sid, dial_number, url)

    timeout = float(env("NEXTGENSWITCH_TIMEOUT", "10"))

    try:
        response = await asyncio.to_thread(
            requests.put,
            url,
            headers=headers,
            data=payload,
            timeout=timeout,
        )
    except Exception as exc:  # noqa: BLE001
        logger.exception("Failed to transfer call {}: {}", call_sid, exc)
        return

    timestamp = datetime.now().strftime("%H:%M:%S")
    if response.ok:
        logger.info(
            "Call {} transferred successfully (status {} at {})",
            call_sid,
            response.status_code,
            timestamp,
        )
    else:
        logger.error(
            "Call {} transfer failed (status {} at {}): {}",
            call_sid,
            response.status_code,
            timestamp,
            response.text,
        )
