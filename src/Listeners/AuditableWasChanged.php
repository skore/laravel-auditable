<?php

namespace SkoreLabs\LaravelAuditable\Listeners;

use Illuminate\Support\Str;
use SkoreLabs\LaravelAuditable\Events\AuditableEvent;

class AuditableWasChanged
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\AuditableEvent  $event
     * @return void
     */
    public function handle(AuditableEvent $event)
    {
        $actionStr = $this->getActionColumn($event->action);

        if (!$event->user && $model->{$actionStr} == $event->user->id) {
            return false;
        }

        if ($event->model->isGuarded($actionStr)) {
            $event->model->{lcfirst(Str::studly($actionStr))}()->associate($event->user->id);
        } else {
            $event->model->{$actionStr} = $event->user->id;
        }
    }

    /**
     * Get action related column of the model.
     *
     * @param string $action
     * @return string
     */
    public function getActionColumn($action)
    {
        switch ($action) {
            default:
            case 'creating':
                return 'created_by';
                break;

            case 'updating':
                return 'updated_by';
                break;

            case 'deleting':
                return 'deleted_by';
                break;
        }
    }
}
