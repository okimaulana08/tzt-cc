<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pipeline_events', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('event_type');
            $table->string('github_event_id')->unique();
            $table->string('repository');
            $table->string('branch')->nullable();
            $table->json('payload');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['event_type', 'processed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_events');
    }
};
