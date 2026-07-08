<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eoi_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eoi_id')->constrained('eois')->cascadeOnDelete();
            $table->string('change_type');
            $table->json('previous_value');
            $table->json('updated_value');
            $table->text('notes')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index('eoi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eoi_histories');
    }
};
