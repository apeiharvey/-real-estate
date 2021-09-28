<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MergeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_users', function (Blueprint $table) {
            $table->text('phone')
                ->after('two_factor_recovery_codes')
                ->nullable();

            $table->text('phone_alt')
                ->after('phone')
                ->nullable();

            $table->boolean('is_active')
                ->after('phone_alt')
                ->default(true)
                ->nullable();

            $table->text('user_type')
                ->after('is_active')
                ->nullable();

            $table->text('user_ref_id')
                ->after('user_type')
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
            $table->dropColumn('phone', 'phone_alt', 'is_active', 'user_type', 'user_ref_id');
        });
    }
}
