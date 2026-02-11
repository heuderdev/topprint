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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document')->nullable(); // CNPJ/CPF se quiser

            $table->boolean('has_custom_pricing')
                ->default(false)
                ->index();

            // FK opcional direto pro profile (1:1)
            $table->foreignId('pricing_profile_id')
                ->nullable()
                ->constrained('pricing_profiles')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
