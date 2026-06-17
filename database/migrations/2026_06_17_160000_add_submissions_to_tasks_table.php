<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('tasks', 'submissions')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->json('submissions')->nullable()->after('comments');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tasks', 'submissions')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('submissions');
            });
        }
    }
};
