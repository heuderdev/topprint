<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'has_custom_pricing',
        'pricing_profile_id',
    ];

    protected $casts = [
        'has_custom_pricing' => 'bool',
    ];

    public function pricingProfile()
    {
        return $this->belongsTo(PricingProfile::class);
    }
}
