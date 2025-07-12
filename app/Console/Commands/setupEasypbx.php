<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class setupEasypbx extends Command
{
    protected $signature = 'easypbx:setup
        {--org= : Organization name}
        {--domain= : Organization domain}
        {--contact= : Contact number}
        {--email= : Email address}
        {--password= : Password}
        {--address= : Company address}';

    protected $description = 'Initial setup for EasyPBX system';

    public function handle()
    {
        $orgName = $this->option('org') ?? $this->ask('Enter your organization name', 'NextGenSwitch');
        $domain = $this->option('domain') ?? $this->ask('Enter your organization domain', 'localhost');
        $contact = $this->option('contact') ?? $this->ask('Enter your contact no', '+100000000');

        $email = $this->option('email');
        while (!$email) {
            $email = $this->ask('Enter your email address');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Please provide a valid email address.');
                $email = null;
            }
        }

        $pass = $this->option('password');
        while (!$pass) {
            $pass = $this->secret('Enter your password (at least 6 characters)');
            if (strlen($pass) < 6) {
                $this->error('Please provide a valid password.');
                $pass = null;
            }
        }

        $address = $this->option('address') ?? $this->ask('Enter your company address', 'Change it');

        $this->info("Organization : $orgName");
        $this->info("Domain       : $domain");
        $this->info("Contact no   : $contact");
        $this->info("Email        : $email");
        $this->info("Address      : $address");

        $isInteractive = $this->option('org') === null ||
            $this->option('domain') === null ||
            $this->option('contact') === null ||
            $this->option('email') === null ||
            $this->option('password') === null ||
            $this->option('address') === null;

        if (!$isInteractive || $this->confirm('Do you want to continue?')) {

            User::createRoleAndPermisssions();

            $organization = Organization::create([
                'name' => $orgName,
                'domain' => $domain,
                'contact_no' => $contact,
                'email' => $email,
                'address' => $address,
                'is_default' => 1,
                'is_primary' => 1,
            ]);

            $user = User::create([
                'organization_id' => $organization->id,
                'name' => $orgName,
                'email' => $email,
                'password' => Hash::make($pass),
                'status' => 1
            ]);

            Artisan::call("easypbx:permission superAdmin $user->id");

            $this->info('Done! Please login from web portal http://127.0.0.1/');
        }
    }
}
