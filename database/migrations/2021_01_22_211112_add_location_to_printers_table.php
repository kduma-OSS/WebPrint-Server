<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToPrintersTable extends Migration
{
    public function up()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->string('location')->after('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
}
