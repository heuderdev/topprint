<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_profile_id',
        'code',
        'bw_price_per_page',
        'color_price_per_page',
        'binding_min_pages',
        'binding_max_pages',
        'binding_price_per_copy',
    ];

    protected $casts = [
        'bw_price_per_page'      => 'float',
        'color_price_per_page'   => 'float',
        'binding_min_pages'      => 'int',
        'binding_max_pages'      => 'int',
        'binding_price_per_copy' => 'float',
    ];

    public function pricingProfile()
    {
        return $this->belongsTo(PricingProfile::class);
    }

    public function isBindingRule(): bool
    {
        return $this->binding_min_pages !== null && $this->binding_max_pages !== null;
    }
}
