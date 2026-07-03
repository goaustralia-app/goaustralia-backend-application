<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('answer_type', ['single_choice', 'date', 'country', 'multiple_choice', 'states', 'occupations']);
            $table->enum('input_type', ['single_select', 'multi_select', 'boolean', 'id', 'date'])->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('condition_question_id')->nullable()->constrained('onboarding_questions')->onDelete('set null');
            // Stored without FK to avoid circular dependency with onboarding_question_options
            $table->unsignedBigInteger('condition_option_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_questions');
    }
};
