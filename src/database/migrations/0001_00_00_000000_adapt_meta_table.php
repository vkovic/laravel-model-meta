<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdaptMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->unsignedBigInteger('metable_id')->default('');
            $table->string('metable_type', 128)->default('');

            $table->unique(['realm', 'metable_type', 'metable_id', 'key']);
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
        });
    }
}