<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_onboarding_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('onboarding_questions')->onDelete('cascade');
            // Q1, Q6: selected option
            $table->foreignId('option_id')->nullable()->constrained('onboarding_question_options')->onDelete('set null');
            // Q2: application date
            $table->date('answer_date')->nullable();
            // Q3: country applied from
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->timestamps();

            $table->unique(['user_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_onboarding_answers');
    }
};
