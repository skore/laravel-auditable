<?php

namespace SkoreLabs\LaravelAuditable\Events;

class AuditableModelIsDeleting extends AuditableEvent
{
    /**
     * Can be "creating", "updating" or "deleting".
     *
     * @var string
     */
    public $action = 'deleting';
}
