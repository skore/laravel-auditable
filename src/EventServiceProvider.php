<?php

namespace SkoreLabs\LaravelAuditable;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsCreating;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsDeleting;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsUpdating;
use SkoreLabs\LaravelAuditable\Listeners\AuditableWasChanged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuditableModelIsCreating::class => [
            AuditableWasChanged::class,
        ],
        AuditableModelIsUpdating::class => [
            AuditableWasChanged::class,
        ],
        AuditableModelIsDeleting::class => [
            AuditableWasChanged::class,
        ],
    ];
}
