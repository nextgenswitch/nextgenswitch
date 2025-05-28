#!/bin/bash
#/usr/local/bin/my-once-script.sh
# Do your actual work here
echo "Running boot script" > /tmp/boot_script_output.txt

# Delete the service and script
rm -f /etc/systemd/system/my-once-script.service
rm -- "$0"

# Reload systemd
systemctl daemon-reload
