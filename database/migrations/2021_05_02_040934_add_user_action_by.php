<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserActionBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_users', function (Blueprint $table) {
            $table->bigInteger('created_by')
                ->after('created_at')
                ->nullable();

            $table->bigInteger('updated_by')
                ->after('updated_at')
                ->nullable();

            $table->bigInteger('deleted_by')
                ->after('deleted_at')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_users', function (Blueprint $table) {
            $table->dropColumn('created_by', 'updated_by', 'deleted_by');
        });
    }
}
