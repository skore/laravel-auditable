<?php

namespace SkoreLabs\LaravelAuditable\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

abstract class AuditableEvent
{
    use Dispatchable;
    use SerializesModels;

    public const CONTAINER_KEY = 'skorelabs.auditable';

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;

    /**
     * @var string
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        if (app()->has(static::CONTAINER_KEY)) {
            [$user, $action] = app()->get(static::CONTAINER_KEY);

            $this->user = ! $action || $this->action === $action ? $user : null;
        }

        $this->user ??= Auth::user();
    }

    /**
     * Set user for auditable event when action, all actions if null.
     */
    public static function setUser(Authenticatable $user, string $action = null): void
    {
        app()->bind(static::CONTAINER_KEY, fn () => [$user, $action]);
    }
}
