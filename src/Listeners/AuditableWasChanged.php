<?php

namespace SkoreLabs\LaravelAuditable\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SkoreLabs\LaravelAuditable\Events\AuditableEvent;

class AuditableWasChanged implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \SkoreLabs\LaravelAuditable\Events\AuditableEvent  $event
     * @return void
     */
    public function handle(AuditableEvent $event)
    {
        $actionStr = $this->getActionColumn($event->action);

        if (!$event->user && $event->model->isGuarded($actionStr) && !$event->model->{$actionStr}) {
            return $this->delete();
        }

        dump($event->action);
        $event->model->{$actionStr} = $event->user->id;
        $event->model->save();
    }

    /**
     * Get action related column of the model.
     *
     * @param mixed $action
     * @return string
     */
    protected function getActionColumn($action)
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
