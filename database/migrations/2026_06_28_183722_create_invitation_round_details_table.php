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
        Schema::create('invitation_round_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_round_id')->constrained('invitation_rounds')->cascadeOnDelete();
            $table->foreignId('occupation_id')->nullable()->constrained('occupation_lists')->nullOnDelete();
            $table->string('occupation');
            $table->string('anzsco_code', 10)->nullable();
            $table->unsignedSmallInteger('points')->nullable()->comment('Points score for this occupation breakdown');
            $table->unsignedInteger('eoi_count')->nullable()->comment('Number of EOIs / invitations at this occupation and points');
            $table->timestamps();

            $table->index('invitation_round_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_round_details');
    }
};
