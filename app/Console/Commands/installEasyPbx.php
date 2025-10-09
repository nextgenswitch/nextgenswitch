<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class installEasyPbx extends Command
{

    /*  EXAMPLE USAGE
        php artisan easypbx:install --db-driver=mysql --db-url="mysql://user:pass@localhost:3306/easypbx"
        php artisan easypbx:install --db-driver=sqlite --sqlite-path="/full/path/to/database.sqlite"
    */


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easypbx:install
    {--db-driver= : Database driver (mysql|sqlite)}
    {--db-url= : Database URL for MySQL (e.g., mysql://user:pass@host:3306/dbname)}
    {--sqlite-path= : SQLite database file path}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ensure running as root (or with sudo)
        $isRoot = false;
        if (PHP_OS_FAMILY !== 'Windows') {
            if (function_exists('posix_geteuid')) {
                $isRoot = posix_geteuid() === 0;
            } else {
                $uid = null;
                try {
                    $uid = @trim((string) shell_exec('id -u 2>/dev/null'));
                } catch (\Throwable $e) {
                    // ignore
                }
                if ($uid !== '' && ctype_digit($uid)) {
                    $isRoot = ($uid === '0');
                } else {
                    $user = @trim((string) shell_exec('whoami 2>/dev/null'));
                    $isRoot = ($user === 'root');
                }
            }
        } else {
            // On Windows, skip root check
            $isRoot = true;
        }

        if (!$isRoot) {
            $this->error('This command must be run as root or with sudo privileges.');
            return;
        }

        $this->info('Welcome to NextGenSwitch installation!');
        //$this->checkDependencies(['sox', 'lame', 'supervisorctl', 'redis-cli', 'iptables', 'mysql', 'apache']);
        $this->checkDependencies(['sox', 'lame', 'supervisorctl','apache']);

        if (!(file_exists('/usr/infosoftbd/nextgenswitch/nextgenswitch') && !is_dir('/usr/infosoftbd/nextgenswitch/nextgenswitch'))) {
            $this->warn('NextGenSwitch not detected. Attempting to unzip bundled package...');

            $zipPath = base_path('setup/nextgenswitch.zip');
            $destDir = '/usr/infosoftbd/nextgenswitch';

            if (!file_exists($zipPath)) {
                $this->error("Package not found: {$zipPath}");
                return;
            }

            if (!is_dir($destDir)) {
                if (!@mkdir($destDir, 0755, true) && !is_dir($destDir)) {
                    $this->error("Failed to create directory: {$destDir}");
                    return;
                }
            }

            $unpacked = false;

            // Try system unzip first
            $unzipBin = trim((string) @shell_exec('command -v unzip 2>/dev/null'));
            if ($unzipBin !== '') {
                $cmd = escapeshellcmd($unzipBin) . ' -o ' . escapeshellarg($zipPath) . ' -d ' . escapeshellarg($destDir);
                $process = Process::fromShellCommandline($cmd);
                $process->run();
                if ($process->isSuccessful()) {
                    $unpacked = true;
                    $this->info("Unzipped with system unzip to {$destDir}");
                } else {
                    $this->warn('System unzip failed: ' . $process->getErrorOutput());
                }
            }

            // Fallback to ZipArchive if available
            if (!$unpacked && class_exists('\ZipArchive')) {
                $zip = new \ZipArchive();
                if ($zip->open($zipPath) === true) {
                    $zip->extractTo($destDir);
                    $zip->close();
                    $unpacked = true;
                    $this->info("Unzipped with ZipArchive to {$destDir}");
                }
            }

            if (!$unpacked) {
                $this->error('Failed to unpack NextGenSwitch. Please install manually.');
                return;
            }

            // Ensure required directories after unzip
            try {
                $logsDir = rtrim($destDir, '/') . '/logs';
                $mediaDir = rtrim($destDir, '/') . '/media';
                foreach ([$logsDir, $mediaDir] as $dirToEnsure) {
                    if (!is_dir($dirToEnsure)) {
                        if (!@mkdir($dirToEnsure, 0755, true) && !is_dir($dirToEnsure)) {
                            $this->warn("Failed to create directory: {$dirToEnsure}");
                        } else {
                            $this->info("Created directory: {$dirToEnsure}");
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->warn('Post-unzip directory setup encountered an error: ' . $e->getMessage());
            }
        }

        // Ensure system user and permissions for NextGenSwitch
        $this->ensureNextGenSwitchUserAndPermissions();

        if (!$this->checkPhpExtensions()) {
            $this->error('Please install the missing PHP extensions.');
            return;
        }

        $this->updateSupervisorConfig(base_path());

        $this->updateHttpdConfig(base_path());

        // Ensure NextGenSwitch lua config from sample and set API URL
        $this->ensureLuaConfig('http://127.0.0.1/api/switch');

        // Choose database driver and connection details
        $dbDriver = $this->option('db-driver') ?? $this->choice('Choose database driver', ['mysql', 'sqlite'], 'mysql');

        $dbUrl = null;
        $sqlitePath = null;

        if ($dbDriver === 'mysql') {
            $dbUrl = $this->option('db-url') ?? $this->ask('Enter your MySQL DATABASE_URL', 'mysql://root@localhost:3306/easypbx');
            $this->info("Database Driver : mysql");
            $this->info("Database URL    : {$dbUrl}");
        } else {
            $defaultSqlitePath = storage_path('database.sqlite');
            $sqlitePath = $this->option('sqlite-path') ?? $this->ask('Enter path for SQLite database file', $defaultSqlitePath);
            $this->info("Database Driver : sqlite");
            $this->info("SQLite Path     : {$sqlitePath}");
        }

        $interactive = is_null($this->option('db-driver'))
            || ($dbDriver === 'mysql' && is_null($this->option('db-url')))
            || ($dbDriver === 'sqlite' && is_null($this->option('sqlite-path')));

        if (!$interactive || $this->confirm('Do you want to continue?')) {

            $this->info('Installing NextGenSwitch...');

            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);

            // Helper to upsert .env key
            $upsertEnv = function (string $content, string $key, string $value) {
                $pattern = '/^' . preg_quote($key, '/') . '=.*/m';
                if (preg_match($pattern, $content)) {
                    return preg_replace($pattern, $key . '=' . $value, $content);
                }
                return rtrim($content, "\r\n") . PHP_EOL . $key . '=' . $value . PHP_EOL;
            };

            if ($dbDriver === 'mysql') {
                // Use DATABASE_URL for MySQL
                $envContent = $upsertEnv($envContent, 'DB_CONNECTION', 'mysql');
                $envContent = $upsertEnv($envContent, 'DATABASE_URL', $dbUrl);
            } else {
                // Ensure sqlite file exists
                $dir = dirname($sqlitePath);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                if (!file_exists($sqlitePath)) {
                    @touch($sqlitePath);
                    @chmod($sqlitePath, 0777);
                }

                $envContent = $upsertEnv($envContent, 'DB_CONNECTION', 'sqlite');
                $envContent = $upsertEnv($envContent, 'DB_DATABASE', $sqlitePath);
            }

            // Detect Redis availability and connectivity; set cache driver accordingly
            $redisHost = env('REDIS_HOST', '127.0.0.1');
            $redisPort = (int) env('REDIS_PORT', 6379);
            $hasClient = extension_loaded('redis') || class_exists('Predis\\Client');
            $canConnect = false;
            if ($hasClient) {
                try {
                    if (extension_loaded('redis')) {
                        $r = new \Redis();
                        if (@$r->connect($redisHost, $redisPort, 1.0)) {
                            $pong = @$r->ping();
                            $canConnect = ($pong === true) || ($pong === '+PONG') || ($pong === 'PONG');
                        }
                    } elseif (class_exists('Predis\\Client')) {
                        $client = new \Predis\Client([
                            'scheme' => 'tcp',
                            'host' => $redisHost,
                            'port' => $redisPort,
                        ]);
                        $client->connect();
                        $client->ping();
                        $canConnect = true;
                    }
                } catch (\Throwable $e) {
                    $canConnect = false;
                }
            }

            if ($hasClient && $canConnect) {
                $envContent = $upsertEnv($envContent, 'CACHE_DRIVER', 'redis');
                config(['cache.default' => 'redis']);
                $this->info('Cache driver set to redis.');
            } else {
                $envContent = $upsertEnv($envContent, 'CACHE_DRIVER', 'file');
                config(['cache.default' => 'file']);
                $this->warn('Redis not available; using file cache.');
            }

            file_put_contents($envPath, $envContent);

            // Removed database existence check and creation per request

            // Run Laravel migrations using selected driver
            if ($dbDriver === 'mysql') {
                config([
                    'database.default' => 'mysql',
                    'database.connections.mysql.url' => $dbUrl,
                ]);
            } else {
                config([
                    'database.default' => 'sqlite',
                    'database.connections.sqlite.database' => $sqlitePath,
                ]);
            }

            $exitCode = $this->call('migrate', [
                '--force' => true,
            ]);

            if ($exitCode !== 0) {
                $this->error('Failed to run database migrations.');
                return;
            }
            $this->info('Database migrations ran successfully.');

            $this->setupPermissions();

            $process = Process::fromShellCommandline("supervisorctl reread && supervisorctl update");
            $process->run();


            $this->info('NextGenSwitch installed successfully!');
        } else {
            $this->info('Installation cancelled.');
        }
    }



    private function checkDependencies(array $dependencies): void
    {
        $distro = $this->getCloudDistro();

        foreach ($dependencies as $dependency) {
            $actualPackage = $this->resolvePackageName($distro, $dependency);

            if (!$this->isInstalled($actualPackage)) {
                $this->error("Dependency missing: {$dependency}");
                $installCommand = $this->getInstallCommand($distro, $actualPackage);
                $this->info("Install using: {$installCommand}");
            }
            // else {
            //     $this->info("Dependency installed: {$dependency}");
            // }
        }
    }

    private function resolvePackageName(string $distro, string $dependency): string
    {
        $webServers = ['apache', 'httpd', 'apache2'];

        if (in_array($dependency, $webServers)) {
            return match (strtolower($distro)) {
                'ubuntu', 'debian' => 'apache2',
                'centos', 'rocky linux', 'alma linux', 'red hat', 'oracle linux' => 'httpd',
                'amazon linux' => 'httpd',
                'fedora' => 'httpd',
                'arch linux' => 'apache',
                default => $dependency,
            };
        }

        return $dependency;
    }

    private function isInstalled(string $package): bool
    {
        $output = shell_exec("which {$package} 2>/dev/null");
        return !empty(trim($output));
    }

    private function getInstallCommand(string $distro, string $package): string
    {
        return match (strtolower($distro)) {
            'ubuntu', 'debian' => "sudo apt install -y {$package}",
            'centos', 'rocky linux', 'alma linux', 'red hat', 'oracle linux' => "sudo yum install -y {$package}",
            'amazon linux' => "sudo amazon-linux-extras install -y {$package}",
            'fedora' => "sudo dnf install -y {$package}",
            'arch linux' => "sudo pacman -S --noconfirm {$package}",
            default => "Unsupported distribution: {$distro}",
        };
    }


    private function getCloudDistro(): string
    {
        if (file_exists('/etc/os-release')) {
            $osRelease = strtolower(file_get_contents('/etc/os-release'));

            $distros = [
                'ubuntu'     => 'Ubuntu',
                'debian'     => 'Debian',
                'centos'     => 'CentOS',
                'rocky'      => 'Rocky Linux',
                'alma'       => 'AlmaLinux',
                'amazon'     => 'Amazon Linux',
                'fedora'     => 'Fedora',
                'red hat'    => 'Red Hat Enterprise Linux',
                'oracle'     => 'Oracle Linux',
                'arch'       => 'Arch Linux',
            ];

            foreach ($distros as $key => $name) {
                if (str_contains($osRelease, $key)) {
                    return $name;
                }
            }
        }

        return 'Unknown';
    }


    private function checkPhpExtensions(): bool
    {
        $extensions = [
            'bcmath',
            'ctype',
            'fileinfo',
            'json',
            'mbstring',
            'openssl',
            'pdo',
            'tokenizer',
            'xml',
        ];
        $allExtensionsInstalled = true;

        foreach ($extensions as $extension) {
            if (!extension_loaded($extension)) {
                $this->error("PHP Extension missing: {$extension}");
                $allExtensionsInstalled = false;
            }
            // else {
            //     $this->info("PHP Extension installed: {$extension}");
            // }
        }

        return $allExtensionsInstalled;
    }

    private function updateSupervisorConfig(string $projectPath): void
    {
        $configFile = "{$projectPath}/setup/supervisord/nextgenswitch.ini";

        if (!file_exists($configFile)) {
            $this->info("Supervisor config file not found: {$configFile}");
            return;
        }

        $configContent = file_get_contents($configFile);
        $updatedContent = str_replace('/var/www/html/easypbx/', rtrim($projectPath, '/') . '/', $configContent);

        $distro = $this->getCloudDistro();
        $supervisorDir = $this->getSupervisorConfigDir($distro);
        $configExtension = $this->getConfigExtension($distro);

        if ($supervisorDir && $configExtension) {
            $destination = "{$supervisorDir}/nextgenswitch{$configExtension}";
            file_put_contents($destination, $updatedContent);
            $this->info("Supervisor config moved to: {$destination}");
        } else {
            $this->info("Unsupported distribution for Supervisor configuration.");
        }
    }

    private function getSupervisorConfigDir(string $distro): ?string
    {
        return match (strtolower($distro)) {
            'ubuntu', 'debian' => '/etc/supervisor/conf.d',
            'centos', 'rocky linux', 'alma linux', 'red hat', 'oracle linux' => '/etc/supervisord.d',
            'amazon linux' => '/etc/supervisord.d',
            'fedora' => '/etc/supervisord.d',
            'arch linux' => '/etc/supervisord.d',
            default => null,
        };
    }

    private function getConfigExtension(string $distro): string
    {
        return match (strtolower($distro)) {
            'ubuntu', 'debian' => '.conf',
            'centos', 'rocky linux', 'alma linux', 'red hat', 'oracle linux',
            'amazon linux', 'fedora', 'arch linux' => '.ini',
            default => '.ini',
        };
    }

    private function updateHttpdConfig(string $projectPath): void
    {
        $configFile = "{$projectPath}/setup/httpd/nextgenswitch.conf";

        if (!file_exists($configFile)) {
            $this->info("HTTPD config file not found: {$configFile}");
            return;
        }

        $configContent = file_get_contents($configFile);
        $updatedContent = str_replace('/var/www/html/easypbx/', rtrim($projectPath, '/') . '/', $configContent);

        $distro = $this->getCloudDistro();
        $httpdDir = $this->getHttpdConfigDir($distro);


        if ($httpdDir) {
            $destination = "{$httpdDir}/nextgenswitch.conf";
            file_put_contents($destination, $updatedContent);
            $this->info("HTTPD config moved to: {$destination}");
        } else {
            $this->info("Unsupported distribution for HTTPD configuration.");
        }
    }

    private function ensureNextGenSwitchUserAndPermissions(): void
    {
        $user = 'nextgenswitch';
        $group = 'nextgenswitch';
        $dir = '/usr/infosoftbd/nextgenswitch';

        $nologin = '/bin/false';
        if (file_exists('/usr/sbin/nologin')) {
            $nologin = '/usr/sbin/nologin';
        } elseif (file_exists('/sbin/nologin')) {
            $nologin = '/sbin/nologin';
        }

        $commands = [
            // Create group if not exists
            "getent group {$group} > /dev/null 2>&1 || groupadd --system {$group}",
            // Create user if not exists
            "id -u {$user} > /dev/null 2>&1 || useradd --system --gid {$group} --no-create-home --shell {$nologin} {$user}",
            // Ensure directory exists
            "mkdir -p {$dir}",
            // Ownership and sane permissions
            "chown -R {$user}:{$group} {$dir}",
            "find {$dir} -type d -exec chmod 755 {} \\; 2>/dev/null || true",
            "find {$dir} -type f -exec chmod 644 {} \\; 2>/dev/null || true",
            // Ensure executables have execute permission
            "chmod 755 {$dir}/nextgenswitch {$dir}/nextgenswitchctl 2>/dev/null || true",
        ];

        foreach ($commands as $command) {
            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                // Not fatal for all steps; warn and continue
                $this->warn("Command failed or not applicable: {$command}\n" . $process->getErrorOutput());
            } else {
                $this->info("Executed: {$command}");
            }
        }

        $this->info("Ensured system user '{$user}' and access to {$dir}");
    }

    private function ensureLuaConfig(string $apiUrl): void
    {
        $configDir = '/usr/infosoftbd/nextgenswitch/lua';
        $configFile = $configDir . '/config.lua';
        $sampleFile = $configFile . '.sample';

        if (!is_dir($configDir)) {
            $this->warn("Lua config directory not found: {$configDir}");
            return;
        }

        // Replace config.lua with sample, if sample exists
        if (file_exists($sampleFile)) {
            if (file_exists($configFile)) {
                @unlink($configFile);
            }
            if (!@copy($sampleFile, $configFile)) {
                $this->warn("Failed to copy sample config: {$sampleFile} -> {$configFile}");
                // Continue; we may still be able to edit existing file if present
            } else {
                $this->info("Initialized Lua config from sample: {$configFile}");
            }
        } elseif (!file_exists($configFile)) {
            $this->warn("Neither config.lua nor config.lua.sample found in {$configDir}");
            return;
        }

        // Ensure SERVER_API_URL is set to the desired value
        if (!file_exists($configFile) || !is_readable($configFile)) {
            $this->warn("Config file not readable: {$configFile}");
            return;
        }

        $contents = @file_get_contents($configFile);
        if ($contents === false) {
            $this->warn("Failed to read: {$configFile}");
            return;
        }

        $updated = false;

        // Build replacement lines
        $newLineLocal = 'local SERVER_API_URL = "' . $apiUrl . '"';
        $newLineGlobal = 'SERVER_API_URL = "' . $apiUrl . '"';

        if (preg_match('/(^|\n)\s*local\s+SERVER_API_URL\s*=\s*([\"\"]).*?\2/s', $contents)) {
            $contents = preg_replace('/(^|\n)\s*local\s+SERVER_API_URL\s*=\s*([\"\"]).*?\2/s', "\n{$newLineLocal}", $contents, 1, $count);
            $updated = $updated || ($count > 0);
        }

        // Else try replacing global assignment
        if (!$updated && preg_match('/(^|\n)\s*SERVER_API_URL\s*=\s*([\"\"]).*?\2/s', $contents)) {
            $contents = preg_replace('/(^|\n)\s*SERVER_API_URL\s*=\s*([\"\"]).*?\2/s', "\n{$newLineGlobal}", $contents, 1, $count);
            $updated = $updated || ($count > 0);
        }

        // If still not present, append a new line
        if (!$updated) {
            $contents = rtrim($contents, "\r\n") . "\n{$newLineGlobal}\n";
            $updated = true;
        }

        // Normalize any accidental backslash-quoted values to single-quoted Lua strings
        $contents = preg_replace('/(^|\\n)\\s*(local\\s+)?SERVER_API_URL\\s*=\\s*\\\\\"(.*?)\\\\\"/s', '$1$2SERVER_API_URL = \'$3\'', $contents);

        if (@file_put_contents($configFile, $contents) === false) {
            $this->warn("Failed to write updated config: {$configFile}");
            return;
        }

        $this->info("Updated SERVER_API_URL in {$configFile} to {$apiUrl}");
    }

    private function getHttpdConfigDir(string $distro): ?string
    {
        return match (strtolower($distro)) {
            'ubuntu', 'debian' => '/etc/apache2/sites-available',
            'centos', 'rocky linux', 'alma linux', 'red hat', 'oracle linux' => '/etc/httpd/conf.d',
            'amazon linux' => '/etc/httpd/conf.d',
            'fedora' => '/etc/httpd/conf.d',
            'arch linux' => '/etc/httpd/conf.d',
            default => null,
        };
    }

    private function setupPermissions(): void
    {
        $basePath = base_path();

        $commands = [
            "find {$basePath}/public -type f -exec chmod 644 {} \\;",
            "find {$basePath}/public -type d -exec chmod 755 {} \\;",
            "chmod -R 0777 {$basePath}/storage",
            "mkdir -p {$basePath}/storage/app/public/records",
            "cp  {$basePath}/setup/iptables-rules {$basePath}/storage/",
            // "ln -s {$basePath}/public/sounds {$basePath}/storage/app/public/sounds",
            // "ln -s {$basePath}/storage/app/public {$basePath}/public/storage",
            // "ln -s {$basePath}/storage/app/public/records /usr/infosoftbd/nextgenswitch/records"
        ];

        if (strtolower($this->getCloudDistro()) != 'ubuntu' && strtolower($this->getCloudDistro()) != 'debian') {
            $commands[] = "sudo chcon -R -t httpd_sys_rw_content_t {$basePath}";
        }

        foreach ($commands as $command) {
            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error($process->getErrorOutput());
                throw new ProcessFailedException($process);
            }

            $this->info("Executed: {$command}");
        }


        $links = [
            "{$basePath}/public/sounds" => "{$basePath}/storage/app/public/sounds",
            "{$basePath}/storage/app/public" => "{$basePath}/public/storage",
            "{$basePath}/storage/app/public/records" => "/usr/infosoftbd/nextgenswitch/records",
            "{$basePath}/storage/iptables-rules" => "/usr/infosoftbd/nextgenswitch/iptables-rules",
        ];

        foreach ($links as $target => $link) {

            if (!file_exists($target)) {
                $this->error("Target does not exist: {$target}");
                continue;
            }

            if (!file_exists($link)) {
                symlink($target, $link);
                $this->info("Created symbolic link: {$link} -> {$target}");
            } else {
                $this->warn("Symbolic link already exists: {$link}");
            }
        
        }
       

        $this->info('Project permissions and symbolic links set successfully.');
    }
}
