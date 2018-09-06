<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditClaimsOrganizationsAddVisibilityColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claims_organizations', function (Blueprint $table) {
            $table->enum('visibility', ['show', 'hide'])->default('hide');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claims_organizations', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
}
