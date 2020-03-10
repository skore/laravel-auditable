<?php

namespace SkoreLabs\LaravelAuditable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        Blueprint::mixin(new Schema());
    }
}
