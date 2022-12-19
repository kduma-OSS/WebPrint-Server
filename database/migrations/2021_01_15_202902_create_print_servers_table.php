<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('print_servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->ulid('ulid')->unique();

            $table->string('name');
            $table->timestamp('last_active_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('print_servers');
    }
};
