<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:admin-user {userName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userName = $this->argument('userName');
        $user = DB::transaction(function () use ($userName) {
            $user = User::create([
                'name' => $userName,
                'email' => $userName . '@example.com',
                'password' => bcrypt(str()->random(32)),
                'is_admin' => true,
            ]);
            return $user;
        });

        $this->info("email: {$user->email}");
        $this->info("password: {$user->password}");
    }
}
