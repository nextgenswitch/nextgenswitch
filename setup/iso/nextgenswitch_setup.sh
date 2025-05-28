#!/bin/bash
# Move the script to /usr/local/bin/nextgenswitch_setup.sh


# Do your actual work here
echo "NextGenSwitch setup script running..." >> /var/log/nextgenswitch_setup.log
mysql -sfu root < "/var/www/html/easypbx/setup/init.sql"
mysql -ueasypbx -peasypbx easypbx < "/var/www/html/easypbx/setup/easypbx.sql"


# Delete the service and script
rm -f /etc/systemd/system/nextgenswitch_setup.service
rm -- "$0"

# Reload systemd
systemctl daemon-reload
