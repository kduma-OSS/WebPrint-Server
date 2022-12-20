<?php

use App\Models\Enums\PrintJobStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('print_jobs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();

            $table->enum('status', collect(PrintJobStatusEnum::cases())->map->value->toArray())
                ->default(PrintJobStatusEnum::New->value);

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

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
