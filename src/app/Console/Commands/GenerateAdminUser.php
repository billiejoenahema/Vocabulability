<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $password = str()->random(32);

        $user = DB::transaction(function () use ($userName, $password) {
            $user = User::create([
                'name' => $userName,
                'email' => $userName . '@example.com',
                'password' => bcrypt($password),
            ]);
            return $user;
        });
        $user->is_admin = true;
        $user->save();

        $this->info("email: {$user->email}");
        $this->info("password: {$password}");
    }
}
