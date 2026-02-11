<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'bool',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function rules()
    {
        return $this->hasMany(PricingRule::class);
    }
}
