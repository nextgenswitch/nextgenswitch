#!/bin/bash
set -euo pipefail

LOG_FILE=/var/log/nextgenswitch_setup.log
{
  echo "$(date '+%Y-%m-%d %H:%M:%S') - NextGenSwitch first-boot setup starting..."

  # Configure database connection for installer (matches installEasyPbx signature)
  DB_DRIVER="mysql"           # options: mysql|sqlite
  DB_HOST="localhost"
  DB_PORT="3306"
  DB_USER="easypbx"
  DB_PASS="easypbx"
  DB_NAME="easypbx"
  SQLITE_PATH=""              # set if DB_DRIVER=sqlite

  if [ "$DB_DRIVER" = "mysql" ]; then
    # Ensure MariaDB is running and ready
    systemctl start mariadb || true
    echo "Waiting for MariaDB to be ready..."
    ready=0
    for i in {1..60}; do
      if mysqladmin ping -uroot --silent; then
        ready=1
        break
      fi
      sleep 2
    done
    if [ "$ready" -ne 1 ]; then
      echo "MariaDB did not become ready in time." >&2
      exit 1
    fi

    # Create database and application user (idempotent)
    mysql -uroot <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
  fi

  # Example DB priming (uncomment if needed)
  # mysql -sfu root < "/var/www/html/easypbx/setup/init.sql"
  # mysql -ueasypbx -peasypbx easypbx < "/var/www/html/easypbx/setup/easypbx.sql"

  cd /var/www/html/easypbx

  if [ "$DB_DRIVER" = "mysql" ]; then
    DB_URL="mysql://${DB_USER}:${DB_PASS}@${DB_HOST}:${DB_PORT}/${DB_NAME}"
    php artisan easypbx:install --db-driver="$DB_DRIVER" --db-url="$DB_URL"
  else
    # Ensure directory exists for sqlite file when provided
    if [ -n "$SQLITE_PATH" ]; then
      mkdir -p "$(dirname "$SQLITE_PATH")"
    fi
    php artisan easypbx:install --db-driver="sqlite" --sqlite-path="$SQLITE_PATH"
  fi

  # Install a first-login message that shows the setup URL once
  cat > /etc/profile.d/nextgenswitch_welcome.sh <<'BASH'
#!/bin/bash
# Show setup URL once on first interactive login
[ -n "$PS1" ] || return
MARKER=/var/tmp/.nextgenswitch_setup_banner_shown
if [ -f "$MARKER" ]; then
  return
fi
if [ -t 1 ]; then
  ip=$(ip -4 -o addr show scope global 2>/dev/null | awk '{print $4}' | cut -d/ -f1 | head -n1)
  if [ -z "$ip" ]; then
    ip=$(hostname -I 2>/dev/null | awk '{print $1}')
  fi
  echo
  if [ -n "$ip" ]; then
    echo "Please browse your IP http://$ip/setup to complete your installation."
  else
    echo "Please browse http://<server-ip>/setup to complete your installation."
  fi
  echo
  : > "$MARKER" 2>/dev/null || true
fi
BASH
  chmod 644 /etc/profile.d/nextgenswitch_welcome.sh

  echo "$(date '+%Y-%m-%d %H:%M:%S') - NextGenSwitch first-boot setup completed."
} >> "$LOG_FILE" 2>&1

# Clean up: disable and remove the unit so it won't run again
if systemctl is-enabled --quiet nextgenswitch_setup.service; then
  systemctl disable --now nextgenswitch_setup.service || true
fi
rm -f /etc/systemd/system/nextgenswitch_setup.service || true
systemctl daemon-reload || true

# Optionally remove this script after execution
rm -- "$0" || true
