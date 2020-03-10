<?php

namespace SkoreLabs\LaravelAuditable\Events;

class AuditableModelIsCreating extends AuditableEvent
{
    /**
     * Can be "creating", "updating" or "deleting".
     *
     * @var string
     */
    public $action = 'creating';
}
