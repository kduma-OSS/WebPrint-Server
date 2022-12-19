<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('pivot_client_application_printer', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_application_id')->constrained('client_applications');
            $table->foreignId('printer_id')->constrained('printers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pivot_client_application_printer');
    }
};
