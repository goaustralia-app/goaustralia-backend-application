<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skillselect_eoi_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eoi_id')->constrained('eois')->cascadeOnDelete();
            $table->date('invited_at');
            $table->foreignId('visa_subclass_id')
                ->nullable()
                ->constrained('visa_subclasses')
                ->nullOnDelete();
            $table->boolean('accepted')->nullable();
            $table->timestamps();

            $table->index('eoi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skillselect_eoi_invitations');
    }
};
