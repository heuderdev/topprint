<?php
// https://www.perplexity.ai/search/preciso-de-uma-libe-composer-c-evQT_6geQii9iqfoz.SQEg
namespace App\Services;

use App\Models\Company;
use App\Models\PricingProfile;
use App\Models\PricingRule;

class PricingService
{
    public function getPaperPrices(Company $company, string $paperType): array
    {
        $profile = $this->resolvePricingProfile($company);

        /** @var PricingRule|null $rule */
        $rule = $profile->rules()
            ->where('code', $paperType)
            ->first();

        if (! $rule) {
            // fallback seguro
            return [0.0, 0.0];
        }

        return [
            (float) $rule->bw_price_per_page,
            (float) $rule->color_price_per_page,
        ];
    }

    public function getBindingPricePerCopy(Company $company, int $pagesPerCopy): float
    {
        $profile = $this->resolvePricingProfile($company);

        /** @var PricingRule|null $rule */
        $rule = $profile->rules()
            ->where('code', 'binding')
            ->whereNotNull('binding_min_pages')
            ->whereNotNull('binding_max_pages')
            ->where('binding_min_pages', '<=', $pagesPerCopy)
            ->where('binding_max_pages', '>=', $pagesPerCopy)
            ->first();

        if (! $rule) {
            return 0.0;
        }

        return (float) $rule->binding_price_per_copy;
    }

    private function resolvePricingProfile(Company $company): PricingProfile
    {
        if ($company->has_custom_pricing && $company->pricingProfile) {
            return $company->pricingProfile;
        }

        return PricingProfile::where('is_default', true)->firstOrFail();
    }
}
