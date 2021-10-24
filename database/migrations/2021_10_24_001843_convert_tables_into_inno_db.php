<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertTablesIntoInnoDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'cart_profile',
            'carts',
            'clients',
            'favoritos',
            'media',
            'migrations',
            'password_resets',
            'post_profile',
            'post_profile',
            'posts',
            'profiles',
            'skills',
            'users',
            'video'
        ];
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'cart_profile',
            'carts',
            'clients',
            'favoritos',
            'media',
            'migrations',
            'password_resets',
            'post_profile',
            'post_profile',
            'posts',
            'profiles',
            'skills',
            'users',
            'video'
        ];
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
        }
    }
}
