<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Transaction;
use App\Policies\PostPolicy;
use App\Auth\CacheUserProvider;
use App\Policies\CommentPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::before(function ($user, $ability) {
        //     if ($user->isSuperAdmin) {
        //         return true;
        //     }
        // });

        Gate::define('complete-send-money', function (User $user, Transaction $transaction) {
            return $user->id === $transaction->sender_id ? Response::allow() : Response::deny('You do not own this transaction!');
        });
    }
}
