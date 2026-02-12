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
        Schema::create('eoi_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eoi_question_id')->constrained()->cascadeOnDelete();
            $table->text('answer_text');
            $table->integer('points');
            $table->text('description')->nullable();
            $table->integer('order_position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoi_answers');
    }
};
