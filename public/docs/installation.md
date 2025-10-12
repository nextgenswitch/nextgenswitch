# NextGenSwitch Installation Guide

This guide is generated directly from the behavior of the `php artisan easypbx:install` command so that you can mirror or troubleshoot the automated setup performed by the EasyPBX installer.

---

## 1. Prerequisites

- **Root or sudo access**  
  The installer aborts unless it can determine you are running as `root`.

- **Bundled assets present**  
  The Laravel project must include:
  - `setup/nextgenswitch.zip`
  - `setup/supervisord/nextgenswitch.ini`
  - `setup/httpd/nextgenswitch.conf`
  - `setup/iptables-rules`

- **System packages in PATH**  
  The script checks these commands and prints OS-specific install hints if any are missing:
  - `sox`
  - `lame`
  - `supervisorctl`
  - Apache (`apache2` on Debian/Ubuntu, `httpd` on RPM-based distros)

- **Required PHP extensions**  
  `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`

> ✅ You can rerun the installer after fixing prerequisites; steps are idempotent.

---

## 2. Running the Installer

From your Laravel project root (where `artisan` lives):

```bash
# Interactive run (prompts for database details)
php artisan easypbx:install

# MySQL non-interactive
php artisan easypbx:install \
  --db-driver=mysql \
  --db-url="mysql://user:pass@localhost:3306/easypbx"

# SQLite non-interactive
php artisan easypbx:install \
  --db-driver=sqlite \
  --sqlite-path="/var/lib/nextgenswitch/database.sqlite"
```

### 2.1 Database prompts

- **MySQL (default)**  
  Provide a full `DATABASE_URL` (e.g. `mysql://user:pass@host:3306/dbname`). The installer writes `DB_CONNECTION=mysql` and `DATABASE_URL=<value>` to `.env`.

- **SQLite**  
  Supply an absolute file path. The installer creates the directory/file with `0777` permissions if absent, then writes `DB_CONNECTION=sqlite` and `DB_DATABASE=<path>` to `.env`.

If you provided CLI options the script skips prompts. Otherwise it confirms the collected values before continuing.

---

## 3. What Happens Under the Hood

| Step | Action | Details |
| --- | --- | --- |
| 1 | **Runtime deployment** | If `/usr/infosoftbd/nextgenswitch/nextgenswitch` is missing, `setup/nextgenswitch.zip` is extracted there. The installer also ensures `/usr/infosoftbd/nextgenswitch/logs` and `/usr/infosoftbd/nextgenswitch/media` exist. |
| 2 | **System user & permissions** | Creates `nextgenswitch` user/group if absent, owns `/usr/infosoftbd/nextgenswitch`, and makes bundled binaries executable. |
| 3 | **PHP extension verification** | Confirms the list in §1; aborts if any are missing. |
| 4 | **Supervisor config** | Copies `setup/supervisord/nextgenswitch.ini` to `/etc/supervisor/conf.d` (Debian/Ubuntu) or `/etc/supervisord.d` (other distros) and normalizes paths to your Laravel project. |
| 5 | **Apache HTTPD config** | Copies `setup/httpd/nextgenswitch.conf` to `/etc/apache2/sites-available` (Debian/Ubuntu) or `/etc/httpd/conf.d` (RPM-based/Arch) with project paths rewritten. |
| 6 | **Lua API config** | Rebuilds `/usr/infosoftbd/nextgenswitch/lua/config.lua` from `.sample` when available and forces `SERVER_API_URL = "http://127.0.0.1/api/switch"`. |
| 7 | **Environment caching** | Tests Redis connectivity using the PHP `redis` extension or `predis/predis`. Sets `CACHE_DRIVER=redis` if healthy, otherwise `CACHE_DRIVER=file`. |
| 8 | **Database migrations** | Applies `php artisan migrate --force` with the chosen driver configuration. A migration failure stops the process to preserve system state. |
| 9 | **Filesystem setup** | Adjusts permissions on `public` (files `644`, directories `755`), makes `storage` writable, copies `setup/iptables-rules` into `storage`, and on SELinux-enabled distros (non Debian/Ubuntu) runs `chcon -R -t httpd_sys_rw_content_t <project>`. |
| 10 | **Symlink creation** | Ensures the following symlinks exist:<br>• `storage/app/public/sounds → public/sounds`<br>• `public/storage → storage/app/public`<br>• `/usr/infosoftbd/nextgenswitch/records → storage/app/public/records`<br>• `/usr/infosoftbd/nextgenswitch/iptables-rules → storage/iptables-rules` |
| 11 | **Supervisor reload** | Executes `supervisorctl reread && supervisorctl update` so new processes are registered. |

Each step logs progress to the console. Non-fatal issues (e.g. an existing symlink) raise warnings but do not halt execution.

---

## 4. Post-Install Checklist

- **Supervisor**: `supervisorctl status` should list the NextGenSwitch programs as `RUNNING`. Investigate `/var/log/supervisor/` for errors.
- **Apache**: On Debian/Ubuntu run `a2ensite nextgenswitch && systemctl reload apache2`. On other distros restart or reload `httpd`.
- **Database**: Verify tables in your MySQL/SQLite database. If migrations failed, rerun `php artisan migrate --force` after fixing the issue.
- **Lua integration**: Confirm `/usr/infosoftbd/nextgenswitch/lua/config.lua` has the correct `SERVER_API_URL` if the web UI is hosted elsewhere.
- **File permissions**: Ensure `/usr/infosoftbd/nextgenswitch` and `storage/` remain writable. Re-run the installer to reapply permissions if needed.

---

## 5. Troubleshooting Tips

- The installer prints distro-aware package commands for any missing dependency—run them and rerun the installer.
- You can safely re-run `php artisan easypbx:install` to refresh symlinks, permissions, Supervisord/Apache configs, or regenerate the Lua API config.
- Runtime logs live in `/usr/infosoftbd/nextgenswitch/logs`. Web logs reside in `/var/log/apache2/` (Debian/Ubuntu) or `/var/log/httpd/` (Red Hat family).
- If Redis is unavailable, confirm `CACHE_DRIVER=file` in `.env` and consider installing/configuring Redis before re-running the installer.

---

## 6. Unattended / CI Usage

Provide all required options when calling the installer to avoid interactive prompts. Example for MySQL:

```bash
DB_URL="mysql://user:password@db.internal:3306/easypbx"
php artisan easypbx:install --db-driver=mysql --db-url="${DB_URL}"
```

For SQLite, ensure the target directory is writable by the user running the command:

```bash
php artisan easypbx:install \
  --db-driver=sqlite \
  --sqlite-path="/usr/infosoftbd/nextgenswitch/easypbx.sqlite"
```

---

## 7. Need Help?

- Product support: [support@nextgenswitch.com](mailto:support@nextgenswitch.com)
- Community & updates: [https://nextgenswitch.com/docs](https://nextgenswitch.com/docs)
- Commercial deployments & professional services: [https://nextgenswitch.com/contact](https://nextgenswitch.com/contact)

Keep this guide handy when operating the installer or preparing infrastructure for automated deployments.
