<?php

namespace Database\Seeders;

use App\Models\PricingProfile;
use App\Models\PricingRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default = PricingProfile::firstOrCreate(
            ['name' => 'default'],
            ['is_default' => true]
        );

        // Tipos de papel
        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $default->id, 'code' => 'ap75_imagem'],
            ['bw_price_per_page' => 0.12, 'color_price_per_page' => 0.20]
        );

        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $default->id, 'code' => 'ap75_4x4'],
            ['bw_price_per_page' => 0.24, 'color_price_per_page' => 0.50]
        );

        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $default->id, 'code' => 'couche_170_4x0'],
            ['bw_price_per_page' => 0.50, 'color_price_per_page' => 0.90]
        );

        // Regras de encadernação (sem code específico; usa só faixa)
        $bindings = [
            [25, 80, 10.0],
            [81, 120, 20.0],
            [121, 200, 30.0],
            [201, 350, 40.0],
            [351, 450, 50.0],
        ];

        foreach ($bindings as [$min, $max, $price]) {
            PricingRule::updateOrCreate(
                [
                    'pricing_profile_id' => $default->id,
                    'code'               => 'binding', // genérico
                    'binding_min_pages'  => $min,
                    'binding_max_pages'  => $max,
                ],
                [
                    'binding_price_per_copy' => $price,
                ]
            );
        }
    }
}
