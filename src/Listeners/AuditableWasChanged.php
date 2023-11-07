<?php

namespace SkoreLabs\LaravelAuditable\Listeners;

use Illuminate\Support\Str;
use SkoreLabs\LaravelAuditable\Events\AuditableEvent;

class AuditableWasChanged
{
    /**
     * Handle the event.
     */
    public function handle(AuditableEvent $event)
    {
        $actionStr = $this->getActionColumn($event->action);

        if (blank($event->user) || $event->model->{$actionStr} == $event->user->id) {
            return false;
        }

        $event->model->{lcfirst(Str::studly($actionStr))}()->associate($event->user->id);

        // TODO: No other way as \Illuminate\Database\Eloquent\SoftDeletingScope::extend()
        // does the trick under Eloquent's query builder...
        if ($event->action === 'deleting') {
            $event->model->saveQuietly();
        }
    }

    /**
     * Get action related column of the model.
     */
    public function getActionColumn(string $action): string
    {
        return match ($action) {
            default    => 'created_by',
            'creating' => 'created_by',
            'updating' => 'updated_by',
            'deleting' => 'deleted_by',
        };
    }
}
