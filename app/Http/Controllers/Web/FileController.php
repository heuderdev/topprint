<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class FileController extends Controller
{
    public function __construct(
        private readonly PricingService $pricingService,
    ) {}

    public function index(Request $request, Company $company)
    {

        $company->update([
            'has_custom_pricing' => 1,
            'pricing_profile_id' => 2,
        ]);

        $paperType             = $request->input('paper_type', 'ap75_imagem');
        $quantidadeCopias      = (int) $request->input('copies', 10);
        $pagsColoridasPorCopia = (int) $request->input('color_pages_per_copy', 1);
        $encadernar            = filter_var($request->input('with_binding', true), FILTER_VALIDATE_BOOL);

        // 1. Preços por página vindos do banco (profile default ou SICOOB/custom)
        [$valorPagePretoBranco, $valorPageColorida] =
            $this->pricingService->getPaperPrices($company, $paperType);

        // 2. Ler PDF (por enquanto fixo, depois você parametriza upload/seleção)
        $filename = '[10 cópias] APOSTILA PAS 3 (pg).pdf';
        $fullPath = storage_path("app/public/{$filename}");

        $parser  = new Parser();
        $pdf     = $parser->parseFile($fullPath);
        $details = $pdf->getDetails();
        $totalPagesOriginal = (int) ($details['Pages'] ?? 0);

        if ($totalPagesOriginal <= 0) {
            return response()->json(['error' => 'PDF sem páginas ou inválido.'], 400);
        }

        if ($pagsColoridasPorCopia > $totalPagesOriginal) {
            return response()->json(['error' => 'Páginas coloridas por cópia maior que o total de páginas.'], 422);
        }

        // 3. Cálculo impressão
        $totalCopiasColoridas = $quantidadeCopias * $pagsColoridasPorCopia;
        $pagsPretoPorCopia    = $totalPagesOriginal - $pagsColoridasPorCopia;
        $totalCopiasPB        = $quantidadeCopias * $pagsPretoPorCopia;

        $totalPretoBranco = $totalCopiasPB * $valorPagePretoBranco;
        $totalColorido    = $totalCopiasColoridas * $valorPageColorida;
        $totalImpressao   = $totalPretoBranco + $totalColorido;

        // 4. Encadernação via service (usa faixas do profile da empresa)
        $bindingPricePerCopy = 0.0;
        $bindingTotal        = 0.0;

        if ($encadernar) {
            $bindingPricePerCopy = $this->pricingService
                ->getBindingPricePerCopy($company, $totalPagesOriginal);

            $bindingTotal = $bindingPricePerCopy * $quantidadeCopias;
        }

        // 5. Total geral
        $totalGeral = $totalImpressao + $bindingTotal;

        return response()->json([
            'company'                  => [
                'id'                => $company->id,
                'name'              => $company->name,
                'has_custom_pricing' => (bool) $company->has_custom_pricing,
                'pricing_profile_id' => $company->pricing_profile_id,
            ],

            'filename'                 => $filename,
            'total_paginas_pdf'        => $totalPagesOriginal,
            'paper_type'               => $paperType,

            'pags_coloridas_por_copia' => $pagsColoridasPorCopia,
            'pags_preto_por_copia'     => $pagsPretoPorCopia,
            'quantidade_copias'        => $quantidadeCopias,

            'total_copias_pb'          => $totalCopiasPB,
            'total_copias_color'       => $totalCopiasColoridas,

            'valor_pb_unit'            => $valorPagePretoBranco,
            'valor_colorida_unit'      => $valorPageColorida,

            'total_preto_branco'       => $totalPretoBranco,
            'total_colorido'           => $totalColorido,
            'total_impressao'          => $totalImpressao,

            'encadernar'               => $encadernar,
            'binding_price_per_copy'   => $bindingPricePerCopy,
            'binding_total'            => $bindingTotal,

            'total_geral'              => $totalGeral,
        ]);
    }
}
