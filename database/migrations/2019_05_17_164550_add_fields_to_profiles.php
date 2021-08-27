<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('zip_code')->nullable();
            $table->string('abilities')->nullable();
            $table->string('languages')->nullable();
            $table->string('favorite_team')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('zip_code');
            $table->dropColumn('abilities');
            $table->dropColumn('languages');
            $table->dropColumn('favorite_team');
        });
    }
}
