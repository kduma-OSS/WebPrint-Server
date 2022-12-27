<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('client_applications', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('team_id')->constrained();

            $table->ulid('ulid')->unique();

            $table->string('name');
            $table->string('url')->nullable();
            $table->timestamp('last_active_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_applications');
    }
};
