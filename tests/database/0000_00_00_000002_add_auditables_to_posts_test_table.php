<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAuditablesToPostsTestTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->auditables(true);
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // TODO: Need MySQL or similar to test this, doesn't work on SQLite, any workaround?
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropAuditables();
            }
        });
    }
}
