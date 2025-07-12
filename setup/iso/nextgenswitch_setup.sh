#!/bin/bash
# Move the script to /usr/local/bin/nextgenswitch_setup.sh


# Do your actual work here
echo "$(date '+%Y-%m-%d %H:%M:%S') - NextGenSwitch setup script running..." >> /var/log/nextgenswitch_setup.log
#mysql -sfu root < "/var/www/html/easypbx/setup/init.sql"
#mysql -ueasypbx -peasypbx easypbx < "/var/www/html/easypbx/setup/easypbx.sql"
cd /var/www/html/easypbx
php artisan easypbx:install \
    --db-host=localhost \
    --db-port=3306 \
    --db-user=easypbx \
    --db-pass=easypbx \
    --db-name=easypbx

# Delete the service and script
# rm -f /etc/systemd/system/nextgenswitch_setup.service
# rm -- "$0"

# Reload systemd
systemctl daemon-reload
