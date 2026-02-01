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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('capital');
            $table->string('timezone');
            $table->string('region_type')->comment('state or territory');
            $table->boolean('is_regional')->default(0);
            $table->string('regional_class')->comment('regional | metropolitan | mixed');
            $table->tinyInteger('migration_priority')->default(0);
            $table->boolean('state_nomination')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
