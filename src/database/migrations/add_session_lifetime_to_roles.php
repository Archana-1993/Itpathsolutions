<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'session_lifetime')) {
                $table->integer('session_lifetime')->default(20); // Default session lifetime 20 min
            }
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'session_lifetime')) {
                $table->dropColumn('session_lifetime');
            }
        });
    }
};
