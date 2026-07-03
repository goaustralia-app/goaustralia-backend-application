<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_onboarding_occupation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('occupation_list_id')->constrained('occupation_lists')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'occupation_list_id'], 'uooa_user_occupation_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_onboarding_occupation_answers');
    }
};
