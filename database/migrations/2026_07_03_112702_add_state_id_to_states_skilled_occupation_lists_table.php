<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states_skilled_occupation_lists', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('subclass_id')->constrained('states')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('states_skilled_occupation_lists', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
        });
    }
};
