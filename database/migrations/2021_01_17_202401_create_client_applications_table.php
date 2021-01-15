<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('client_applications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('uuid')->unique();

            $table->string('name');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_applications');
    }
}
