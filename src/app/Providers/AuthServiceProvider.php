<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Item;
use App\Models\Question;
use App\Policies\ItemPolicy;
use App\Policies\QuestionPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Item::class => ItemPolicy::class,
        Question::class => QuestionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(static function ($user, string $token) {
            return env('HOME_URL') . '/password-reset?email=' . $user->email . '&token=' . $token;
        });
    }
}
