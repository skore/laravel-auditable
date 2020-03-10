<?php

namespace SkoreLabs\LaravelAuditable;

class Schema
{
    /**
     * Add nullable unix timestamps.
     *
     * @return \Closure
     */
    public function unixTimestamps()
    {
        return function ($softDeletes = false) {
            $this->integer('created_at')->nullable();
            $this->integer('updated_at')->nullable();

            if ($softDeletes) {
                $this->integer('deleted_at')->nullable();
            }
        };
    }

    /**
     * Indicate that unix timestamps columns should dropped.
     *
     * @return \Closure
     */
    public function dropUnixTimestamps()
    {
        return function ($softDeletes = false) {
            $columnsArr = ['created_at', 'updated_at'];

            if ($softDeletes) {
                $columnsArr[] = 'deleted_at';
            }

            $this->dropColumn($columnsArr);
        };
    }

    /**
     * Add nullable auditable "created_by" tables.
     *
     * @return \Closure
     */
    public function auditables()
    {
        return function ($softDeletes = false, $fkTable = null) {
            if (!$fkTable) {
                $fkTable = 'users';
            }

            $this->unsignedBigInteger('created_by')->nullable();
            $this->foreign('created_by')
                ->references('id')
                ->on($fkTable);

            $this->unsignedBigInteger('updated_by')->nullable();
            $this->foreign('updated_by')
                ->references('id')
                ->on($fkTable);

            if ($softDeletes) {
                $this->unsignedBigInteger('deleted_by')
                    ->nullable();
                $this->foreign('deleted_by')
                    ->references('id')
                    ->on($fkTable);
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
