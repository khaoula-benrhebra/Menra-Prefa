<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_commande',
        'statut',
        'total',
        'commentaire',
        'moyen_paiement'
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ligne_commande')
                    ->withPivot('quantite')
                    ->withTimestamps();
    }

    public function isModifiable()
    {
        return $this->statut === 'en_attente';
    }
}