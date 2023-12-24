<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'brandId'
    ];

    public function brand_category_products(){
        return $this-> hasMany(brand_category_product::class);
    }
}
