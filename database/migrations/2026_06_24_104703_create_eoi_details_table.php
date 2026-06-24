<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eoi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eoi_id')->constrained('eois')->cascadeOnDelete();
            $table->foreignId('eoi_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_answer_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->timestamps();

            $table->index(['eoi_id', 'eoi_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eoi_details');
    }
};
