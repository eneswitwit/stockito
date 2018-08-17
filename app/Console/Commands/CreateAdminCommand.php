<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {email=admin@stockito.com} {password=stockito123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        Admin::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $this->info('User Admin has been created with email '.$email.' and password \''.$password.'\'');

        return 0;
    }
}
