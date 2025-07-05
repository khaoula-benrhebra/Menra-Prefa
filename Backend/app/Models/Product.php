<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    protected $fillable = [
        'nom',
        'description',
        'prix_unitaire',
        'stock_min',
        'stock_actuel',
        'category_id'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Définir les collections de médias
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])
            ->singleFile();
    }

    /**
     * Conversions d'images (optionnel - pour les miniatures)
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);

        $this->addMediaConversion('preview')
            ->width(300)
            ->height(300)
            ->sharpen(10);
    }

    /**
     * Accesseur pour récupérer facilement l'URL de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('products');
    }

    /**
     * Accesseur pour récupérer facilement l'URL de la miniature
     */
    public function getImageThumbUrlAttribute()
    {
        return $this->getFirstMediaUrl('products', 'thumb');
    }
}