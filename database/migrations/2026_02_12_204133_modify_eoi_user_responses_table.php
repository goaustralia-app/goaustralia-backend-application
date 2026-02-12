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
        Schema::table('eoi_user_responses', function (Blueprint $table) {
            $table->integer('points')->after('eoi_answer_id');
        });
    }

    public function down(): void
    {
        Schema::table('eoi_user_responses', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
