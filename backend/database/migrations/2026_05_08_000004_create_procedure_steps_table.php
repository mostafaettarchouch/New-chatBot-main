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
        Schema::create('procedure_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_procedure_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->text('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('documents_needed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_steps');
    }
};