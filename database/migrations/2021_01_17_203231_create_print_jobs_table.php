<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintJobsTable extends Migration
{
    public function up()
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->enum('status', ['new', 'printing', 'finished', 'failed'])->default('new');
            $table->string('status_message')->nullable();

            $table->foreignId('printer_id')->constrained('printers');
            $table->foreignId('client_application_id')->nullable()->constrained('client_applications');

            $table->string('name');

            $table->boolean('ppd')->default(0);
            $table->jsonb('ppd_options')->nullable();

            $table->text('content')->nullable();
            $table->string('content_file')->nullable();

            $table->string('file_name');

            $table->integer('size');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('print_jobs');
    }
}
