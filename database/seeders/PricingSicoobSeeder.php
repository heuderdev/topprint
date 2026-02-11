<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\PricingProfile;
use App\Models\PricingRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSicoobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * PROFILE DEFAULT (genérico)
         */
        $default = PricingProfile::firstOrCreate(
            ['name' => 'default'],
            ['is_default' => true]
        );

        // Tipos de papel - DEFAULT
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

        // Regras de encadernação - DEFAULT
        $bindingsDefault = [
            [25, 80, 10.0],
            [81, 120, 20.0],
            [121, 200, 30.0],
            [201, 350, 40.0],
            [351, 450, 50.0],
        ];

        foreach ($bindingsDefault as [$min, $max, $price]) {
            PricingRule::updateOrCreate(
                [
                    'pricing_profile_id' => $default->id,
                    'code'               => 'binding',
                    'binding_min_pages'  => $min,
                    'binding_max_pages'  => $max,
                ],
                [
                    'binding_price_per_copy' => $price,
                ]
            );
        }

        /**
         * PROFILE CUSTOM SICOOB
         * (valores inventados para exemplo, condições melhores pro banco)
         */
        $sicoobProfile = PricingProfile::firstOrCreate(
            ['name' => 'sicoob_2026'],
            ['is_default' => false]
        );

        // SICOOB: dá um desconto em p/b e cor, e encadernação mais barata
        // Papel Ap75 com imagem, texto, borda
        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $sicoobProfile->id, 'code' => 'ap75_imagem'],
            [
                'bw_price_per_page'    => 0.10, // antes 0.12
                'color_price_per_page' => 0.18, // antes 0.20
            ]
        );

        // Papel simples Ap75grs, texto ou chapada 4/4 cores
        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $sicoobProfile->id, 'code' => 'ap75_4x4'],
            [
                'bw_price_per_page'    => 0.22, // antes 0.24
                'color_price_per_page' => 0.45, // antes 0.50
            ]
        );

        // Papel couchê 170grs, texto ou chapada 4/0 cores
        PricingRule::updateOrCreate(
            ['pricing_profile_id' => $sicoobProfile->id, 'code' => 'couche_170_4x0'],
            [
                'bw_price_per_page'    => 0.45, // antes 0.50
                'color_price_per_page' => 0.85, // antes 0.90
            ]
        );

        // Encadernação SICOOB (um pouco mais barato em todas faixas)
        $bindingsSicoob = [
            [25, 80, 9.0],
            [81, 120, 18.0],
            [121, 200, 27.0],
            [201, 350, 36.0],
            [351, 450, 45.0],
        ];

        foreach ($bindingsSicoob as [$min, $max, $price]) {
            PricingRule::updateOrCreate(
                [
                    'pricing_profile_id' => $sicoobProfile->id,
                    'code'               => 'binding',
                    'binding_min_pages'  => $min,
                    'binding_max_pages'  => $max,
                ],
                [
                    'binding_price_per_copy' => $price,
                ]
            );
        }

        /**
         * EMPRESA SICOOB COM PROFILE CUSTOM
         */
        $sicoob = Company::firstOrCreate(
            ['document' => '00.000.000/0001-00'], // CNPJ fictício
            [
                'name'               => 'SICOOB',
                'has_custom_pricing' => true,
            ]
        );

        // vincula profile custom ao SICOOB
        if (! $sicoob->pricing_profile_id) {
            $sicoob->pricing_profile_id = $sicoobProfile->id;
            $sicoob->save();
        }
    }
}
