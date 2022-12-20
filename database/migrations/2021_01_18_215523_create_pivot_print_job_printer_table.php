<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('pivot_print_job_printer', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('print_job_promise_id')->constrained('print_job_promises');
            $table->foreignId('printer_id')->constrained('printers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pivot_print_job_printer');
    }
};
