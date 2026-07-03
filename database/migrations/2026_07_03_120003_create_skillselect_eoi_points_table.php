<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skillselect_eoi_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eoi_id')->constrained('eois')->cascadeOnDelete();
            $table->string('category');   // e.g. "age", "english", "overseas_employment"
            $table->string('label');      // raw label from SkillSelect, e.g. "Age 25-32"
            $table->integer('points');
            $table->timestamps();

            $table->index('eoi_id');
            $table->unique(['eoi_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skillselect_eoi_points');
    }
};
