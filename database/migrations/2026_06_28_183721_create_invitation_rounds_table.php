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
        Schema::create('invitation_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subclass_id')->nullable()->constrained('visa_subclasses')->nullOnDelete();
            $table->date('round_date');
            $table->unsignedInteger('number_of_invitations')->default(0)->comment('Total EOIs invited / invitations issued this round');
            $table->unsignedInteger('number_of_applications')->nullable()->comment('Number of EOIs in the pool');
            $table->unsignedSmallInteger('minimum_points')->nullable()->comment('Lowest ranked points score invited');
            $table->dateTime('tie_break_date')->nullable()->comment('Visa date of effect used as tie-breaker');
            $table->timestamps();

            $table->index(['round_date', 'subclass_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_rounds');
    }
};
