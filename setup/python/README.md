# WebRTC Bridge Docker Image

This directory contains the assets required to package `webrtc_bridge.py` in a Docker container so it can be deployed as a self-contained service.

## Prerequisites
- Docker 20.10+ installed on the host machine

## Build the image
Run the build command from the repository root so the Docker context includes the `setup/python` directory:

```bash
docker build -t webrtc-bridge -f setup/python/Dockerfile .
```

## Run the container
Publish the app's HTTP port (defaults to `8080`) and supply any environment overrides, such as a different `WEBSOCKET_URI`:

```bash
docker run -d \
  --name webrtc_bridge \
  -p 8080:8080 \
  -e WEBSOCKET_URI=wss://janussg.nextgenswitch.com/websocket/? \
  webrtc-bridge
```

Visit `http://localhost:8080/` to open the demo page. Each WebRTC client negotiates its own WebSocket bridge inside the container.

## Managing the container
- View logs: `docker logs -f webrtc_bridge`
- Stop the service: `docker stop webrtc_bridge`
- Remove the container: `docker rm webrtc_bridge`

You can script these commands or add the container to a Docker Compose stack if you need additional services (TLS proxy, monitoring, etc.).

## Running with a Virtual Environment
To run the bridge directly on the host while keeping dependencies isolated, create a virtual environment inside `setup/python`:

```bash
cd /usr/share/nginx/html/laravel/easypbx/setup/python
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
python webrtc_bridge.py
```

When finished, exit the environment with `deactivate`. On Windows PowerShell use `python -m venv .venv`, then `.\.venv\Scripts\Activate.ps1`, followed by `pip install -r requirements.txt` and `python webrtc_bridge.py`.

## Running in Background with `nohup`
After activating the virtual environment, you can detach the script using `nohup` so it survives the current shell session:

```bash
cd /usr/share/nginx/html/laravel/easypbx/setup/python
source .venv/bin/activate
nohup python webrtc_bridge.py > ../../storage/logs/webrtc_bridge.log 2>&1 &
```

Record the printed PID if you need to stop it later (`kill <pid>`). Tail logs with `tail -f ../../storage/logs/webrtc_bridge.log`. Ensure the Laravel `storage/logs` directory exists and is writable.

## Running with Supervisor
To keep the bridge running on a non-container host, create a Supervisor program file (for example `/etc/supervisor/conf.d/webrtc_bridge.conf`). This example assumes the virtual environment above has been created and populated:

```
[program:webrtc_bridge]
command=/usr/share/nginx/html/laravel/easypbx/setup/python/.venv/bin/python /usr/share/nginx/html/laravel/easypbx/setup/python/webrtc_bridge.py
directory=/usr/share/nginx/html/laravel/easypbx/setup/python
environment=WEBSOCKET_URI="wss://janussg.nextgenswitch.com/websocket/?"
autostart=true
autorestart=true
stopsignal=TERM
stdout_logfile=/usr/share/nginx/html/laravel/easypbx/storage/logs/webrtc_bridge.out.log
stderr_logfile=/usr/share/nginx/html/laravel/easypbx/storage/logs/webrtc_bridge.err.log
user=www-data
```

Adjust the interpreter path, repo location, environment variables, and service user to match your system. Ensure the `.venv` exists (`python3 -m venv .venv && .venv/bin/pip install -r requirements.txt`) before starting the service. Then reload Supervisor with `supervisorctl reread` and `supervisorctl update`. Manage the service via `supervisorctl status|start|stop webrtc_bridge` and tail logs with `supervisorctl tail -f webrtc_bridge`.
