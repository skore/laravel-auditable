<?php

namespace SkoreLabs\LaravelAuditable;

/**
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class Schema
{
    public function auditables()
    {
        /**
         * Add nullable auditable "created_by", "updated_by" or "deleted_by" columns.
         *
         * @param bool   $softDeletes
         * @param string $foreignTable
         *
         * @return void
         */
        return function (bool $softDeletes = false, string $foreignTable = 'users') {
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

    public function dropAuditables()
    {
        /**
         * Indicate that the auditable columns should be dropped.
         *
         * @param bool $softDeletes
         *
         * @return void
         */
        return function (bool $softDeletes = false) {
            $columnsArr = ['created_by', 'updated_by'];

            if ($softDeletes) {
                $columnsArr[] = 'deleted_by';
            }

            $this->dropForeign($columnsArr);
            $this->dropColumn($columnsArr);
        };
    }
}
