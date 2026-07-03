<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states_skilled_occupation_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subclass_id')->constrained('visa_subclasses')->cascadeOnDelete();
            $table->string('anzsco_code', 10);
            $table->string('occupation');
            $table->boolean('subclass_491_eligible')->default(false)->comment('Eligible for Skilled Work Regional visa (subclass 491)');
            $table->boolean('subclass_190_eligible')->default(false)->comment('Eligible for Skilled Nominated visa (subclass 190)');
            $table->text('additional_information')->nullable();
            $table->enum('category', ['onshore', 'offshore']);
            $table->string('financial_year', 10)->comment('e.g. 2026-2027');
            $table->timestamps();

            $table->index(['anzsco_code', 'financial_year']);
            $table->index(['subclass_id', 'financial_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states_skilled_occupation_lists');
    }
};
