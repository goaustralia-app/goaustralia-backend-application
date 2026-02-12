<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eoi_user_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_answer_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'eoi_question_id']);
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoi_user_responses');
    }
};
