<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\Attendee;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // protected $policies = [
    //     //only if you want to override the default laravel behavior
    // ];

    // protected $policies = [
    //     Event::class => EventPolicy::class,
    // ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
	            $request->user()?->id ?: $request->ip()
	          );
        });

        Gate::define('update-event', function($user, Event $event){
                return $user->id === $event->user_id;
        });

        Gate::define('delete_attendee', function($user, Event $event, Attendee $attendee){
                return $user->id === $event->user_id || $user->id === $attendee->user_id ;
        });


        // Gate::define('update-post', function (User $user, Post $post) {
        //     return $user->id === $post->user_id;
        // });

        // Gate::define('update-post', [PostPolicy::class, 'update']);
    }
}


// Authorization changes
// Change 1 (Authorization with Gates lecture and later)

// There's no AuthServiceProvider file anymore in Laravel 11.

// Solution

// The only provider class now is App\Providers\AppServiceProvider - just add the gate definitions inside this file. Source

// Change 2 (Authorization with Policies lecture and later)

// No more $this->authorize('update', $post) inside the controller.

// Solution

// // Add this at the top
// use Illuminate\Support\Facades\Gate;

// // Instead of $this->authorize(...) call Gate::authorize(...)
// // passing the arguments the same way as before
// Gate::authorize('update', $post);
// Source
