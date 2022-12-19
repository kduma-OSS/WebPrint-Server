<?php

use App\Models\Enums\PrintJobPromiseStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('print_job_promises', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();

            $table->enum('status', collect(PrintJobPromiseStatusEnum::cases())->map->value->toArray())
                ->default(PrintJobPromiseStatusEnum::Draft->value);

            $table->foreignId('client_application_id')->constrained('client_applications');
            $table->foreignId('print_job_id')->nullable()->constrained('print_jobs');
            $table->foreignId('printer_id')->nullable()->constrained('printers');

            $table->string('name');

            $table->string('type')->default('raw');
            $table->jsonb('ppd_options')->nullable();

            $table->text('content')->nullable();
            $table->string('content_file')->nullable();

            $table->string('file_name')->nullable();

            $table->integer('size')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('print_job_promises');
    }
};
