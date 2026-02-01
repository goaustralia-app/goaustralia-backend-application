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
        Schema::create('occupation_lists', function (Blueprint $table) {
            $table->id();
            $table->string('occupation');
            $table->string('anzsco_code', 10);
            $table->string('assessing_authority')->nullable();
            $table->string('list')->comment('CSOL | MLTSSL | STSOL | ROL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupation_lists');
    }
};
