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
        Schema::create('unanswered_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->boolean('resolved')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamp('asked_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unanswered_questions');
    }
};