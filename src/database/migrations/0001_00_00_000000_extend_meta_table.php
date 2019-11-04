<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->unsignedBigInteger('metable_id')->default(0)->after('type');
            $table->string('metable_type')->default('')->after('metable_id');

            $table->unique(['realm', 'metable_type', 'metable_id', 'key']);

            // Drop previous (vkovic/laravel-meta) unique
            $table->dropUnique(['realm', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->dropColumn('metable_id');
            $table->dropColumn('metable_type');

            $table->dropUnique(['realm', 'metable_type', 'metable_id', 'key']);

            // Return previous (vkovic/laravel-meta) unique
            $table->unique(['realm', 'key']);
        });
    }
}
