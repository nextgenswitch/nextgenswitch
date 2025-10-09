FROM python:3.11-slim

ENV PYTHONUNBUFFERED=1 \
    PIP_NO_CACHE_DIR=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        pkg-config \
        ffmpeg \
        libavdevice-dev \
        libavfilter-dev \
        libavformat-dev \
        libswscale-dev \
        libopus-dev \
        libvpx-dev \
        libsrtp2-dev \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY setup/python/requirements.txt ./
RUN pip install -r requirements.txt

COPY setup/python/webrtc_bridge.py ./

EXPOSE 8080

CMD ["python", "webrtc_bridge.py"]
