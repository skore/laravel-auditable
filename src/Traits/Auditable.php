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
        $dispatchesEvents = [];

        if ($this->getCreatedAtColumn()) {
            $dispatchesEvents['creating'] = AuditableModelIsCreating::class;
            $attrs[] = 'created_by';
        }

        if ($this->getUpdatedAtColumn()) {
            $dispatchesEvents['updating'] = AuditableModelIsUpdating::class;
            $attrs[] = 'updated_by';
        }

        if ($this->checkDeletedAttr()) {
            $dispatchesEvents['deleting'] = AuditableModelIsDeleting::class;
            $attrs[] = 'deleted_by';
        }

        $this->guarded = array_merge($this->guarded, $attrs);
        $this->hidden = array_merge($this->hidden, $attrs);
        $this->dispatchesEvents = array_merge_recursive($this->dispatchesEvents, $dispatchesEvents);
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
