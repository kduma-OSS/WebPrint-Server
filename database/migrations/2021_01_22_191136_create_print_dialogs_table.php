<?php

use App\Models\Enums\PrintDialogStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('print_dialogs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();

            $table->enum('status', collect(PrintDialogStatusEnum::cases())->map->value->toArray())
                ->default(PrintDialogStatusEnum::New->value);

            $table->foreignId('print_job_promise_id')->unique()->constrained('print_job_promises');
            $table->boolean('auto_print')->default(1);
            $table->string('redirect_url')->nullable();
            $table->ipAddress('restricted_ip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_dialogs');
    }
};
