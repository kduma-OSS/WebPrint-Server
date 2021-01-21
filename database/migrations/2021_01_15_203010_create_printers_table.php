<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintersTable extends Migration
{
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->foreignId('print_server_id')->constrained('print_servers');
            $table->string('name');
            $table->boolean('enabled')->default(1);

            $table->boolean('ppd_support')->default(0);
            $table->jsonb('ppd_options')->nullable();

            $table->jsonb('raw_languages_supported');

            $table->string('uri');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printers');
    }
}
