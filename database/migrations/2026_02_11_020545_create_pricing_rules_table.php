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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_profile_id')
                ->constrained('pricing_profiles')
                ->cascadeOnDelete();

            // código da regra / tipo de papel
            // ex.: ap75_imagem, ap75_4x4, couche_170_4x0
            $table->string('code')->index();

            // preços por página
            $table->decimal('bw_price_per_page', 10, 4)->nullable();
            $table->decimal('color_price_per_page', 10, 4)->nullable();

            // faixas de páginas para encadernação
            // se a linha representar apenas papel e não encadernação, deixa null
            $table->unsignedInteger('binding_min_pages')->nullable();
            $table->unsignedInteger('binding_max_pages')->nullable();
            $table->decimal('binding_price_per_copy', 10, 2)->nullable();

            // opção: evitar duplicidade de code dentro do mesmo profile
            $table->unique(['pricing_profile_id', 'code', 'binding_min_pages', 'binding_max_pages'], 'pricing_rules_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
