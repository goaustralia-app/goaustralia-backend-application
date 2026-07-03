<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_onboard')->default(false)->after('country_id');
            $table->boolean('is_subscribed')->default(false)->after('is_onboard');
            $table->boolean('is_eoi_signed_up')->default(false)->after('is_subscribed');
            $table->boolean('is_calculated')->default(false)->after('is_eoi_signed_up');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_onboard', 'is_subscribed', 'is_eoi_signed_up', 'is_calculated']);
        });
    }
};
