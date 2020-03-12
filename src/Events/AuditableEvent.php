<?php

namespace SkoreLabs\LaravelAuditable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

abstract class AuditableEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;

    /**
     * Can be "creating", "updating" or "deleting".
     *
     * @var string
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->user = Auth::user();
    }
}
