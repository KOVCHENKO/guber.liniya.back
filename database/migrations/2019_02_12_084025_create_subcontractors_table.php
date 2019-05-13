<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubcontractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('claim_id')->nullable();
            $table->foreign('claim_id')->references('id')->on('claims')->onDelete('cascade');
            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->text('description');
            $table->enum('status', ['opened', 'closed'])->default('opened');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subcontractors');
    }
}
