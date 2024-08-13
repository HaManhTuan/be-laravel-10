<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultstringLength(191);

        $minDuration = env('QUERY_LOG_MIN_DURATION', 'none');

        if ($minDuration !== 'none') {
            DB::listen(function ($query) use ($minDuration) {
                if ($query->time >= (double) $minDuration) {
                    $time = microtime(true);
                    Log::debug("SQLQuery - time: " . $time . " duration(ms): " . $query->time . " query: " . $query->sql,
                        ['bindings' => $query->bindings]);
                }
            });
        }
    }
}
