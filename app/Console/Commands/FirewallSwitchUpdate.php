<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;


class FirewallSwitchUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall:switch-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the firewall rules for EasyPBX';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting firewall switch command");
        $iptablesRules = storage_path('iptables-rules');

        if (file_exists($iptablesRules)) {
            shell_exec("iptables-restore < {$iptablesRules}");
        } else {
            $this->info('The iptables rules file does not exist.');
        }

        // shell_exec("ipset create nextgenswitch_temp_block hash:ip timeout 0 2>/dev/null");
        // shell_exec("iptables -C INPUT -m set --match-set nextgenswitch_temp_block src -j DROP 2>/dev/null || iptables -I INPUT 1 -m set --match-set nextgenswitch_temp_block src -j DROP");
        // shell_exec("iptables -C FORWARD -m set --match-set nextgenswitch_temp_block src -j DROP 2>/dev/null || iptables -I FORWARD 1 -m set --match-set nextgenswitch_temp_block src -j DROP");

        // TEMPORARY BLOCK SET
        shell_exec("ipset create nextgenswitch_temp_block hash:ip timeout 0 2>/dev/null");
        shell_exec("iptables -C INPUT -m set --match-set nextgenswitch_temp_block src -j DROP 2>/dev/null || iptables -I INPUT 1 -m set --match-set nextgenswitch_temp_block src -j DROP");
        shell_exec("iptables -C FORWARD -m set --match-set nextgenswitch_temp_block src -j DROP 2>/dev/null || iptables -I FORWARD 1 -m set --match-set nextgenswitch_temp_block src -j DROP");

        // PERMANENT BLOCK SET
        shell_exec("ipset create nextgenswitch_perm_block hash:ip 2>/dev/null"); // no timeout
        shell_exec("iptables -C INPUT -m set --match-set nextgenswitch_perm_block src -j DROP 2>/dev/null || iptables -I INPUT 1 -m set --match-set nextgenswitch_perm_block src -j DROP");
        shell_exec("iptables -C FORWARD -m set --match-set nextgenswitch_perm_block src -j DROP 2>/dev/null || iptables -I FORWARD 1 -m set --match-set nextgenswitch_perm_block src -j DROP");

        $this->info("Firewall IP sets initialized successfully.");
    }
}
