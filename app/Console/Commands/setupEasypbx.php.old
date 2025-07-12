<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class setupEasypbx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easypbx:setup';

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
        $orgName = $this->ask('Enter your organization name', 'NextGenSwitch');
        $domain = $this->ask('Enter your organization domain', 'localhost');
        $contact = $this->ask('Enter your contact no', '+100000000');

        $email = null;
        while (!$email) {
            $email = $this->ask('Enter your email address');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Please provide a valid email address.');
                $email = null;
            }
        }


        $pass = null;
        while (!$pass) {
            $pass = $this->secret('Enter your password (at least 6 characters)');

            if (empty($pass) || strlen($pass) < 6) {
                $this->error('Please provide a valid password.');
                $pass = null;
            }
        }

        $address = $this->ask('Enter your company address', "Change it");

        $this->info("Organization : $orgName");
        $this->info("Domian : $domain");
        $this->info("Contact no : $contact");
        $this->info("Email Address : $email");
        $this->info("Address : $address");

        if ($this->confirm('Do you want to continue?')) {

            User::createRoleAndPermisssions();

            $organization = Organization::create([
                'name' => $orgName,
                'domain' => $domain,
                'contact_no' => $contact,
                'email' => $email,
                'address' => $address,
                'default' => true,
                'is_primary' => true,
            ]);


            $user = User::create([
                'organization_id' => $organization->id,
                'name' => $orgName,
                'email' => $email,
                'password' => Hash::make($pass),
            ]);


            Artisan::call("easypbx:permission superAdmin $user->id");

            $this->info('Done! please login from web portal http://127.0.0.1/');
        }
    }
}
