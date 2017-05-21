<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
\DB::listen(function ($query) {
    $sqlParts = explode('?', $query->sql);
    $bindings = $query->connection->prepareBindings($query->bindings);
    $pdo = $query->connection->getPdo();
    $sql = array_shift($sqlParts);
    foreach ($bindings as $i => $binding) {
        $sql .= $pdo->quote($binding) . $sqlParts[$i];
    }
    
    \Log::info($sql);
});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
