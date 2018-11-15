<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EditClaimsStatusTable extends Migration
{
    /**
     * EditClaimsTableMakeEmailNullable constructor.
     * Возможность оставить тип enum в БД
     */
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE claims MODIFY status ENUM('created', 'assigned', 'executed', 'rejected') NOT NULL default 'created'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE claims MODIFY status ENUM('created', 'assigned', 'executed') NOT NULL default 'created'");
    }
}
