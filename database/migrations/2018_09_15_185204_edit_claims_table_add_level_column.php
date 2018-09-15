<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditClaimsTableAddLevelColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * уровень/масштаб проблемы - личная, общезначимая
     */
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('level')->nullable();
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
            $table->dropColumn('level');
        });
    }
}
