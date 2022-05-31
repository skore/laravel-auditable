<?php

namespace SkoreLabs\LaravelAuditable\Traits;

use Closure;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsCreating;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsDeleting;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsUpdating;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Auditable
{
    /**
     * @var bool
     */
    protected $auditableDisabled = false;

    /**
     * Boot the auditable trait for the model.
     *
     * @return void
     */
    public static function bootAuditable()
    {
        static::creating(function ($model) {
            if (! $model->auditableDisabled && $model->getCreatedAtColumn()) {
                event(new AuditableModelIsCreating($model));
            }
        });
        
        static::replicating(function ($model) {
            if (! $model->auditableDisabled && $model->getCreatedAtColumn()) {
                event(new AuditableModelIsCreating($model));
            }
        });

        static::updating(function ($model) {
            if (! $model->auditableDisabled && $model->getUpdatedAtColumn()) {
                event(new AuditableModelIsUpdating($model));
            }
        });

        static::deleting(function ($model) {
            if (! $model->auditableDisabled && $model->checkDeletedAttr()) {
                event(new AuditableModelIsDeleting($model));
            }
        });
    }

    /**
     * Initialize the auditable trait for an instance.
     *
     * @return void
     */
    public function initializeAuditable()
    {
        $attrs = [];

        if ($this->getCreatedAtColumn()) {
            $attrs[] = 'created_by';
        }

        if ($this->getUpdatedAtColumn()) {
            $attrs[] = 'updated_by';
        }

        if ($this->checkDeletedAttr()) {
            $attrs[] = 'deleted_by';
        }

        $this->guarded = array_merge($this->guarded, $attrs);
        $this->hidden = array_merge($this->hidden, $attrs);
    }

    /**
     * Get the user that create this.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->createdBy();
    }

    /**
     * Get the user that create this.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Get the last user that update this.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
    }

    /**
     * Get the user that delete this.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|void
     */
    public function deletedBy()
    {
        if ($this->checkDeletedAttr()) {
            return $this->belongsTo(config('auth.providers.users.model'), 'deleted_by');
        }
    }

    /**
     * Checks that the softdelete attribute is available.
     *
     * @return bool
     */
    protected function checkDeletedAttr()
    {
        return isset(class_uses(self::class)[SoftDeletes::class]);
    }

    /**
     * Disable auditable events for this model instance.
     *
     * @return $this
     */
    public function disableAuditable()
    {
        $this->auditableDisabled = true;

        return $this;
    }

    /**
     * Disable auditable events for this model instance.
     *
     * @return $this
     */
    public function enableAuditable()
    {
        $this->auditableDisabled = false;

        return $this;
    }

    /**
     * Run callback function without auditable events.
     *
     * @param \Closure $callback
     * @return mixed
     */
    public function withoutAuditable(Closure $callback)
    {
        $this->disableAuditable();

        $result = $callback();

        $this->enableAuditable();

        return $result;
    }
}
