<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Lister tous les produits (route publique)
     */
    public function index()
    {
        $products = Product::with(['category', 'media', 'rawMaterials'])->get();

        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_unitaire' => $product->prix_unitaire,
                'stock_min' => $product->stock_min,
                'stock_actuel' => $product->stock_actuel,
                'category' => $product->category,
                'raw_materials' => $product->rawMaterials->map(function ($rawMaterial) {
                    return [
                        'id' => $rawMaterial->id,
                        'nom' => $rawMaterial->nom,
                        'prix_unitaire' => $rawMaterial->prix_unitaire,
                        'quantite_par_unite' => $rawMaterial->pivot->quantite_par_unite
                    ];
                }),
                'image' => $product->getFirstMediaUrl('products') 
                    ? url($product->getFirstMediaUrl('products')) 
                    : null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json([
            'message' => 'Liste des produits récupérée avec succès',
            'products' => $products
        ]);
    }

    /**
     * Afficher un produit spécifique (route publique)
     */
    public function show($id)
    {
        $product = Product::with(['category', 'media', 'rawMaterials'])->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produit non trouvé'
            ], 404);
        }

        return response()->json([
            'message' => 'Produit récupéré avec succès',
            'product' => [
                'id' => $product->id,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_unitaire' => $product->prix_unitaire,
                'stock_min' => $product->stock_min,
                'stock_actuel' => $product->stock_actuel,
                'category' => $product->category,
                'raw_materials' => $product->rawMaterials->map(function ($rawMaterial) {
                    return [
                        'id' => $rawMaterial->id,
                        'nom' => $rawMaterial->nom,
                        'prix_unitaire' => $rawMaterial->prix_unitaire,
                        'quantite_par_unite' => $rawMaterial->pivot->quantite_par_unite
                    ];
                }),
                'image' => $product->getFirstMediaUrl('products') 
                    ? url($product->getFirstMediaUrl('products')) 
                    : null,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]
        ]);
    }

    /**
     * Créer un nouveau produit 
     */
    public function store(Request $request)
    {
        $this->authorize('create_product');

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'raw_materials' => 'required|array|min:1',
            'raw_materials.*.id' => 'required|exists:raw_materials,id',
            'raw_materials.*.quantite_par_unite' => 'required|numeric|min:0.01',
        ]);

        $product = Product::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
            'category_id' => $request->category_id,
        ]);

        // Attacher les matières premières avec leurs quantités
        foreach ($request->raw_materials as $rawMaterial) {
            $product->rawMaterials()->attach($rawMaterial['id'], [
                'quantite_par_unite' => $rawMaterial['quantite_par_unite']
            ]);
        }

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')
                ->usingName($request->nom)
                ->toMediaCollection('products');
        }

        $product->load(['category', 'media', 'rawMaterials']);

        return response()->json([
            'message' => 'Produit créé avec succès',
            'product' => [
                'id' => $product->id,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_unitaire' => $product->prix_unitaire,
                'stock_min' => $product->stock_min,
                'stock_actuel' => $product->stock_actuel,
                'category' => $product->category,
                'raw_materials' => $product->rawMaterials->map(function ($rawMaterial) {
                    return [
                        'id' => $rawMaterial->id,
                        'nom' => $rawMaterial->nom,
                        'prix_unitaire' => $rawMaterial->prix_unitaire,
                        'quantite_par_unite' => $rawMaterial->pivot->quantite_par_unite
                    ];
                }),
                'image' => $product->getFirstMediaUrl('products') 
                    ? url($product->getFirstMediaUrl('products')) 
                    : null,
            ]
        ], 201);
    }

    /**
     * Modifier un produit 
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update_product');

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produit non trouvé'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'raw_materials' => 'required|array|min:1',
            'raw_materials.*.id' => 'required|exists:raw_materials,id',
            'raw_materials.*.quantite_par_unite' => 'required|numeric|min:0.01',
        ]);

        $product->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
            'category_id' => $request->category_id,
        ]);

        // Détacher toutes les matières premières existantes
        $product->rawMaterials()->detach();

        // Attacher les nouvelles matières premières avec leurs quantités
        foreach ($request->raw_materials as $rawMaterial) {
            $product->rawMaterials()->attach($rawMaterial['id'], [
                'quantite_par_unite' => $rawMaterial['quantite_par_unite']
            ]);
        }

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('products');
            $product->addMediaFromRequest('image')
                ->usingName($request->nom)
                ->toMediaCollection('products');
        }

        $product->load(['category', 'media', 'rawMaterials']);

        return response()->json([
            'message' => 'Produit modifié avec succès',
            'product' => [
                'id' => $product->id,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_unitaire' => $product->prix_unitaire,
                'stock_min' => $product->stock_min,
                'stock_actuel' => $product->stock_actuel,
                'category' => $product->category,
                'raw_materials' => $product->rawMaterials->map(function ($rawMaterial) {
                    return [
                        'id' => $rawMaterial->id,
                        'nom' => $rawMaterial->nom,
                        'prix_unitaire' => $rawMaterial->prix_unitaire,
                        'quantite_par_unite' => $rawMaterial->pivot->quantite_par_unite
                    ];
                }),
                'image' => $product->getFirstMediaUrl('products') 
                    ? url($product->getFirstMediaUrl('products')) 
                    : null,
            ]
        ]);
    }

    /**
     * Supprimer un produit 
     */
    public function destroy($id)
    {
        $this->authorize('delete_product');

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produit non trouvé'
            ], 404);
        }

        // Détacher les matières premières avant suppression
        $product->rawMaterials()->detach();
        
        // Supprimer les médias associés avant de supprimer le produit
        $product->clearMediaCollection('products');
        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ]);
    }
}