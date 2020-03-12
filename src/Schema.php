<?php

namespace SkoreLabs\LaravelAuditable;

class Schema
{
    /**
     * Add nullable auditable "created_by" tables.
     *
     * @return \Closure
     */
    public function auditables()
    {
        return function ($softDeletes = false, $foreignTable = null) {
            if (!$foreignTable) {
                $foreignTable = 'users';
            }

            $this->unsignedBigInteger('created_by')->nullable();
            $this->foreign('created_by')
                ->references('id')
                ->on($foreignTable);

            $this->unsignedBigInteger('updated_by')->nullable();
            $this->foreign('updated_by')
                ->references('id')
                ->on($foreignTable);

            if ($softDeletes) {
                $this->unsignedBigInteger('deleted_by')
                    ->nullable();
                $this->foreign('deleted_by')
                    ->references('id')
                    ->on($foreignTable);
            }
        };
    }

    /**
     * Indicate that auditable columns should be dropped.
     *
     * @return \Closure
     */
    public function dropAuditables()
    {
        return function ($softDeletes = false) {
            $columnsArr = ['created_by', 'updated_by'];

            if ($softDeletes) {
                $columnsArr[] = 'deleted_by';
            }

            $this->dropForeign($columnsArr);
            $this->dropColumn($columnsArr);
        };
    }
}
