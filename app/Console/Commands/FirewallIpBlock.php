<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\IpBlackList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirewallIpBlock extends Command
{
    protected $signature = 'firewall:ip-block';
    protected $description = 'Block IPs temporarily using ipset based on a CSV file and DB sync';

    public function handle()
    {
        $this->info("Starting Firewall IP Block Command");

        while (True) {

            try {

                // Load settings from cache or DB
                $firewallSettingsKey = 'firewall_settings';
                if (Cache::has($firewallSettingsKey)) {
                    info("Using cached firewall settings");
                    $settings = json_decode(Cache::get($firewallSettingsKey), true);
                } else {
                    $settings = Setting::getSettings('firewall');
                    Cache::put($firewallSettingsKey, json_encode($settings), 3600);
                }

                $banTime = isset($settings['ban_time']) ? (int)$settings['ban_time'] : 600;

                // Temporary IPs from CSV
                $temporaryBlockFile = storage_path('temp_block.csv');
                if (file_exists($temporaryBlockFile)) {
                    $lines = file($temporaryBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $ip = trim($line);
                        if (filter_var($ip, FILTER_VALIDATE_IP)) {
                            shell_exec("ipset add nextgenswitch_temp_block {$ip} timeout {$banTime} 2>/dev/null");
                            $this->info("Temporarily blocked IP: {$ip} for {$banTime} seconds");
                        }
                    }
                    unlink($temporaryBlockFile);
                    $this->info("Temp block file processed and deleted.");
                } else {
                    $this->info("No temporary block file found, skipping.");
                }

                // Permanent IPs from CSV
                $permanentBlockFile = storage_path('permanent_block.csv');
                if (file_exists($permanentBlockFile)) {
                    // Remove all existing IPs from the permanent block set
                    info("Flushing existing permanent block IPs");
                    shell_exec("ipset flush nextgenswitch_perm_block 2>/dev/null");

                    $lines = file($permanentBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $ip = trim($line);
                        if (filter_var($ip, FILTER_VALIDATE_IP)) {
                            shell_exec("ipset add nextgenswitch_perm_block {$ip} 2>/dev/null");
                            $this->info("Permanently blocked IP: {$ip}");
                        }
                    }
                    unlink($permanentBlockFile);
                    $this->info("Permanent block file processed and deleted.");
                } else {
                    $this->info("No permanent block file found, skipping.");
                }

                $this->info("Firewall IP sync completed.");
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
                Log::error('FirewallIpBlock Error: ' . $e->getMessage());
            }

            sleep(30); // Sleep for 30 seconds before the next iteration
        }
    }
}
