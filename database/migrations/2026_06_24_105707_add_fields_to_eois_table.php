<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->string('eoi_number')->nullable()->after('user_id');
            $table->date('submission_date')->nullable()->after('eoi_number');
            $table->integer('total_points')->default(0)->after('submission_date');
            $table->foreignId('subclass_id')->nullable()->after('total_points')->constrained('visa_subclasses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->dropForeign(['subclass_id']);
            $table->dropColumn(['eoi_number', 'submission_date', 'total_points', 'subclass_id']);
        });
    }
};
