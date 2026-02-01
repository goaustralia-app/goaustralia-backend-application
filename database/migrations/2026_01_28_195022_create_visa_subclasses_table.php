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
        Schema::create('visa_subclasses', function (Blueprint $table) {
            $table->id();
            // Identity
            $table->string('subclass_code', 10);
            $table->string('name');
            $table->string('slug');
            $table->string('stream')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);

            // Nature
            $table->boolean('is_permanent')->default(false);
            $table->boolean('is_provisional')->default(false);
            $table->integer('validity_months')->nullable();
            $table->boolean('pathway_to_pr')->default(false);

            // Points & nomination
            $table->boolean('points_tested')->default(true);
            $table->integer('min_points_required')->nullable();
            $table->boolean('requires_nomination')->default(false);
            $table->boolean('requires_sponsorship')->default(false);

            // Policy
            $table->integer('annual_cap')->nullable();
            $table->date('last_policy_update')->nullable();
            $table->integer('processing_time_months')->nullable();
            $table->integer('visa_cost_aud')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_subclasses');
    }
};
