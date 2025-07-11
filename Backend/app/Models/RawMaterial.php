<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'nom',
        'prix_unitaire',
        'stock_min',
        'stock_actuel',
    ];

    protected $casts = [
       'prix_unitaire' =>'decimal:2',
    ];

    public function products(){
        return $this->belongsToMany(Product::class, 'product_raw_material')
            ->withPivot('quantite_par_unite')  
            ->withTimestamps();
    }
}