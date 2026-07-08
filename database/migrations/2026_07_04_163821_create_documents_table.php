<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eoi_id')->nullable()->constrained('eois')->nullOnDelete();
            $table->string('document_type');
            $table->string('document_name');
            $table->date('expiry_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('attachment_url')->nullable();
            $table->unsignedInteger('reminder_days')->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'document_type']);
            $table->index(['user_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
