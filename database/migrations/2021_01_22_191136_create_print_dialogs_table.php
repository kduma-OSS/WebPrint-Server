<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintDialogsTable extends Migration
{
    public function up()
    {
        Schema::create('print_dialogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->enum('status', ['new', 'canceled', 'sent'])->default('new');

            $table->foreignId('print_job_promise_id')->unique()->constrained('print_job_promises');
            $table->boolean('auto_print')->default(1);
            $table->string('redirect_url')->nullable();
            $table->ipAddress('restricted_ip')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('print_dialogs');
    }
}
