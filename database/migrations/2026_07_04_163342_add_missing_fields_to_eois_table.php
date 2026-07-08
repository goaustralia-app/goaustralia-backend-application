<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->date('expiry_date')->nullable()->after('submission_date');
            $table->string('state_nomination')->nullable()->after('expiry_date');
            $table->text('notes')->nullable()->after('state_nomination');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->dropColumn(['expiry_date', 'state_nomination', 'notes']);
            $table->dropSoftDeletes();
        });
    }
};
