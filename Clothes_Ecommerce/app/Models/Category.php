<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'categoryId'
    ];
    public function brand_category_products(){
        return $this-> hasMany(brand_category_product::class);
    }
}
