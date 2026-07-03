<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->foreignId('occupation_id')
                ->nullable()
                ->after('subclass_id')
                ->constrained('occupation_lists')
                ->nullOnDelete();

            $table->enum('eoi_status', ['active', 'withdrawn', 'expired'])
                ->nullable()
                ->after('occupation_id');

            $table->integer('total_points_assessed')->nullable()->after('total_points');

            $table->timestamp('scraped_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('eois', function (Blueprint $table) {
            $table->dropForeign(['occupation_id']);
            $table->dropColumn(['occupation_id', 'eoi_status', 'total_points_assessed', 'scraped_at']);
        });
    }
};
