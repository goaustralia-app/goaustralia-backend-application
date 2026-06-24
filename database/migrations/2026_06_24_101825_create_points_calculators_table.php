<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_calculator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_answer_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->timestamps();

            $table->index(['user_id', 'eoi_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_calculator');
    }
};
