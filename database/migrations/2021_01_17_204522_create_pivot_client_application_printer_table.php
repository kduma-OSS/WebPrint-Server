<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('pivot_client_application_printer', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('client_application_id')->constrained('client_applications');
            $table->foreignId('printer_id')->constrained('printers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pivot_client_application_printer');
    }
};
