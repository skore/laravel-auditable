<?php

namespace SkoreLabs\LaravelAuditable\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsCreating;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsDeleting;
use SkoreLabs\LaravelAuditable\Events\AuditableModelIsUpdating;

trait Auditable
{
    /**
     * Initialize the auditable trait for an instance.
     *
     * @return void
     */
    public function initializeAuditable()
    {
        $attrs = [];

        if ($this->getCreatedAtColumn()) {
            static::creating(function () {
                event(new AuditableModelIsCreating($this));
            });

            $attrs[] = 'created_by';
        }

        if ($this->getUpdatedAtColumn()) {
            static::updating(function () {
                event(new AuditableModelIsUpdating($this));
            });

            $attrs[] = 'updated_by';
        }

        if ($this->checkDeletedAttr()) {
            static::deleting(function () {
                event(new AuditableModelIsDeleting($this));
            });

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
}
