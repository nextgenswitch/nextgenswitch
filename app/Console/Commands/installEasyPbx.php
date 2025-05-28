<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class installEasyPbx extends Command
{

    /*  EXAMPLE USAGE
        php artisan easypbx:install \
        --db-host=localhost \
        --db-port=3306 \
        --db-user=root \
        --db-pass=secret \
        --db-name=easypbx
    */


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easypbx:install
    {--db-host= : Database host}
    {--db-port= : Database port}
    {--db-user= : Database user}
    {--db-pass= : Database password}
    {--db-name= : Database name}';


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
        $this->info('Welcome to EasyPBX installation!');
        $this->checkDependencies(['sox', 'lame', 'supervisorctl', 'redis-cli', 'iptables', 'mysql', 'apache']);

        if (!(file_exists('/usr/infosoftbd/nextgenswitch/nextgenswitch') && !is_dir('/usr/infosoftbd/nextgenswitch/nextgenswitch'))) {
            $this->error('EasyPBX is not installed. Please install first.');
            return;
        }

        if (!$this->checkPhpExtensions()) {
            $this->error('Please install the missing PHP extensions.');
            return;
        }

        $this->updateSupervisorConfig(base_path());

        $this->updateHttpdConfig(base_path());



        $dbHost = $this->option('db-host') ?? $this->ask('Enter your database host', 'localhost');
        $dbHostPort = $this->option('db-port') ?? $this->ask('Enter your database port', '3306');
        $dbUser = $this->option('db-user') ?? $this->ask('Enter your database user', 'root');
        $dbPass = $this->option('db-pass') ?? $this->secret('Enter your database password');
        $dbName = $this->option('db-name') ?? $this->ask('Enter your database name', 'easypbx');


        $this->info("Database Host : $dbHost");
        $this->info("Database Port : $dbHostPort");
        $this->info("Database User : $dbUser");
        $this->info("Database Password : $dbPass");
        $this->info("Database Name : $dbName");

        $interactive = !$this->option('db-host') || !$this->option('db-port') || !$this->option('db-user') || !$this->option('db-pass') || !$this->option('db-name');

        if (!$interactive || $this->confirm('Do you want to continue?')) {

            $this->info('Installing EasyPBX...');

            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);

            $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST=$dbHost", $envContent);
            $envContent = preg_replace('/DB_PORT=.*/', "DB_PORT=$dbHostPort", $envContent);
            $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE=$dbName", $envContent);
            $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME=$dbUser", $envContent);
            $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$dbPass", $envContent);

            file_put_contents($envPath, $envContent);

            try {
                $pdo = new \PDO("mysql:host=$dbHost;port=$dbHostPort", $dbUser, $dbPass);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $this->info("Database `$dbName` created or already exists.");
            } catch (\PDOException $e) {
                $this->error('Failed to create the database: ' . $e->getMessage());
                return;
            }

            $sqlFilePath = base_path('setup/easypbx.sql');
            if (file_exists($sqlFilePath)) {
                $command = sprintf(
                    'mysql -h%s -P%s -u%s -p%s %s < %s',
                    escapeshellarg($dbHost),
                    escapeshellarg($dbHostPort),
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbName),
                    escapeshellarg($sqlFilePath)
                );

                $output = null;
                $returnVar = null;
                exec($command, $output, $returnVar);

                if ($returnVar !== 0) {
                    $this->error('Failed to import the SQL file.');
                    return;
                } else {
                    $this->info('SQL file imported successfully.');
                }
            } else {
                $this->error('SQL file not found at ' . $sqlFilePath);
                return;
            }

            $this->info('EasyPBX installed successfully!');
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
}
