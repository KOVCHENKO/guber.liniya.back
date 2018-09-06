<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditClaimsTableAddPidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->unsignedInteger('pid')->unsigned()->nullable();
            $table->foreign('pid')->references('id')->on('claims');
            $table->unsignedInteger('call_id')->unsigned()->nullable();
            $table->foreign('call_id')->references('id')->on('calls');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['pid']);
            $table->dropForeign(['call_id']);
            $table->dropColumn('pid');
            $table->dropColumn('call_id');
        });
    }
}
