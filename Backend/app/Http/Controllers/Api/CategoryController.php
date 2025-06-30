<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Lister toutes les catégories (accessible à tous)
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'message' => 'Liste des catégories récupérée avec succès',
            'categories' => $categories
        ]);
    }

    /**
     * Afficher une catégorie spécifique (accessible à tous)
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        return response()->json([
            'message' => 'Catégorie récupérée avec succès',
            'category' => $category
        ]);
    }

    /**
     * Créer une nouvelle catégorie 
     */
    public function store(Request $request)
    {
        
        $this->authorize('create_categories');

        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:1000',
        ]);

        $category = Category::create([
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Catégorie créée avec succès',
            'category' => $category
        ], 201);
    }

    /**
     * Mettre à jour une catégorie 
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update_categories');

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($id)
            ],
            'description' => 'required|string|max:1000',
        ]);

        $category->update([
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Catégorie modifiée avec succès',
            'category' => $category
        ]);
    }

    /**
     * Supprimer une catégorie 
     */
    public function destroy($id)
    {
        $this->authorize('delete_categories');

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }
}