<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class FileController extends Controller
{
    public function index(Request $request)
    {
        // request (pode vir de form/Livewire)
        $paperType             = $request->input('paper_type', 'ap75_imagem');
        $quantidadeCopias      = (int) $request->input('copies', 10);
        $pagsColoridasPorCopia = (int) $request->input('color_pages_per_copy', 1);
        $encadernar            = (bool) $request->input('with_binding', true);

        // 1. Preço por página conforme tipo de papel
        [$valorPagePretoBranco, $valorPageColorida] = $this->getPaperPrices($paperType);

        // 2. Ler PDF
        $filename = 'Imagens - Sim6D1 (final).pdf';
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

        // 3. Cálculo impressão (p/b + colorido)
        $totalCopiasColoridas = $quantidadeCopias * $pagsColoridasPorCopia;
        $pagsPretoPorCopia    = $totalPagesOriginal - $pagsColoridasPorCopia;
        $totalCopiasPB        = $quantidadeCopias * $pagsPretoPorCopia;

        $totalPretoBranco = $totalCopiasPB * $valorPagePretoBranco;
        $totalColorido    = $totalCopiasColoridas * $valorPageColorida;
        $totalImpressao   = $totalPretoBranco + $totalColorido;

        // 4. Cálculo encadernação (por apostila)
        $bindingPricePerCopy = 0.0;
        $bindingTotal        = 0.0;

        if ($encadernar) {
            $bindingPricePerCopy = $this->getBindingPricePerCopy($totalPagesOriginal);
            $bindingTotal        = $bindingPricePerCopy * $quantidadeCopias;
        }

        // 5. Total geral (impressão + encadernação)
        $totalGeral = $totalImpressao + $bindingTotal;

        return response()->json([
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
        ], 200);
    }

    private function getPaperPrices(string $paperType): array
    {
        switch ($paperType) {
            case 'ap75_imagem':
                return [0.12, 0.20]; // p/b, color

            case 'ap75_4x4':
                return [0.24, 0.50];

            case 'couche_170_4x0':
                return [0.50, 0.90];

            default:
                return [0.12, 0.20];
        }
    }

    private function getBindingPricePerCopy(int $pagesPerCopy): float
    {
        if ($pagesPerCopy >= 25 && $pagesPerCopy <= 80) {
            return 10.0;
        }

        if ($pagesPerCopy >= 81 && $pagesPerCopy <= 120) {
            return 20.0;
        }

        if ($pagesPerCopy >= 121 && $pagesPerCopy <= 200) {
            return 30.0;
        }

        if ($pagesPerCopy >= 201 && $pagesPerCopy <= 350) {
            return 40.0;
        }

        if ($pagesPerCopy >= 351 && $pagesPerCopy <= 450) {
            return 50.0;
        }

        return 0.0;
    }
}
