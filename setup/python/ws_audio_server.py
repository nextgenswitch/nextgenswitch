#!/usr/bin/env python3
"""
μ-law WebSocket test server with WAV playback
----------------------------------------------
- Echoes any audio from clients
- Streams a WAV file as μ-law to all clients
"""

import asyncio
import websockets
import wave
import numpy as np

PORT = 9040
WAV_FILE = "test.wav"       # mono, 16-bit, 8 kHz

clients = set()

# μ-law helper
def pcm2ulaw(pcm_bytes: bytes) -> bytes:
    samples = np.frombuffer(pcm_bytes, dtype=np.int16).astype(np.float32) / 32768.0
    mu = 255.0
    ulaw = np.sign(samples) * np.log1p(mu * np.abs(samples)) / np.log1p(mu)
    return ((ulaw + 1) / 2 * 255 + 0.5).astype(np.uint8).tobytes()

async def handler(ws):
    clients.add(ws)
    print("Client connected")
    try:
        async for msg in ws:
            if isinstance(msg, bytes):
                print(f"[WS Server] Received {len(msg)} bytes")
                # echo back to others
                for c in clients:
                    if c != ws:
                        await c.send(msg)
    except websockets.ConnectionClosed:
        pass
    finally:
        clients.remove(ws)
        print("Client disconnected")

async def wav_streamer():
    """Continuously stream WAV file as μ-law to all clients."""
    while True:
        try:
            with wave.open(WAV_FILE, "rb") as wf:
                assert wf.getnchannels() == 1, "WAV must be mono"
                assert wf.getsampwidth() == 2, "16-bit PCM required"
                assert wf.getframerate() == 8000, "Must be 8kHz"

                chunk = 160  # 20 ms @ 8kHz
                while True:
                    pcm = wf.readframes(chunk)
                    if not pcm:
                        break
                    ulaw = pcm2ulaw(pcm)
                    if clients:
                        await asyncio.gather(*(c.send(ulaw) for c in clients))
                        print(f"[WS Server] Sent {len(ulaw)} bytes from WAV to {len(clients)} client(s)")
                    await asyncio.sleep(0.02)
        except FileNotFoundError:
            print(f"WAV file '{WAV_FILE}' not found — waiting…")
            await asyncio.sleep(5)

async def main():
    server = websockets.serve(handler, "0.0.0.0", PORT)
    await asyncio.gather(server, wav_streamer())

if __name__ == "__main__":
    asyncio.run(main())
