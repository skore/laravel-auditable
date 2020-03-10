<?php

namespace SkoreLabs\LaravelAuditable\Events;

class AuditableModelIsUpdating extends AuditableEvent
{
    /**
     * Can be "creating", "updating" or "deleting".
     *
     * @var string
     */
    public $action = 'updating';
}
