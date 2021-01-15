<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintServersTable extends Migration
{
    public function up()
    {
        Schema::create('print_servers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->string('name');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('print_servers');
    }
}
