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
        $products = Product::with(['category', 'media'])->get();

        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix_unitaire' => $product->prix_unitaire,
                'stock_min' => $product->stock_min,
                'stock_actuel' => $product->stock_actuel,
                'category' => $product->category,
                'image' => $product->getFirstMediaUrl() ?: null,
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
        $product = Product::with(['category', 'media'])->find($id);

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
                'image' => $product->getFirstMediaUrl() ?: null,
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
            'description' => 'required|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection();
        }

        $product->load(['category', 'media']);

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
                'image' => $product->getFirstMediaUrl() ?: null,
            ]
        ], 201);
    }

    /**
     * Modifier un produit $
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
            'description' => 'required|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection();
            $product->addMediaFromRequest('image')->toMediaCollection();
        }

        $product->load(['category', 'media']);

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
                'image' => $product->getFirstMediaUrl() ?: null,
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

        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ]);
    }
}