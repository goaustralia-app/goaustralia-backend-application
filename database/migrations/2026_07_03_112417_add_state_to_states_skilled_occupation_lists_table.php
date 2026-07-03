<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states_skilled_occupation_lists', function (Blueprint $table) {
            $table->string('state', 10)->after('subclass_id')->comment('Australian state/territory: NSW, VIC, QLD, SA, WA, TAS, NT, ACT');
            $table->index('state');
        });
    }

    public function down(): void
    {
        Schema::table('states_skilled_occupation_lists', function (Blueprint $table) {
            $table->dropIndex(['state']);
            $table->dropColumn('state');
        });
    }
};
