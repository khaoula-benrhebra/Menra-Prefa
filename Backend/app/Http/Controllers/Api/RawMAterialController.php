<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rawMaterials = RawMaterial::all();

        return response()->json([
            'message' => 'Liste des matières premières récupérée avec succès',
            'raw_materials' => $rawMaterials,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_rawMaterial');

        $request->validate([
            'nom' => 'required|string|max:255',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
        ]);

        $rawMaterial = RawMaterial::create([
            'nom' => $request->nom,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
        ]);

        return response()->json([
            'message' => 'Matière première créée avec succès',
            'raw_material' => $rawMaterial
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rawMaterial = RawMaterial::find($id);

        if (!$rawMaterial) {
            return response()->json([
                'message' => 'Matière première non trouvée'
            ], 404);
        }

        return response()->json([
            'message' => 'Matière première récupérée avec succès',
            'raw_material' => $rawMaterial
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update_rawMaterial');

        $rawMaterial = RawMaterial::find($id);

        if (!$rawMaterial) {
            return response()->json([
                'message' => 'Matière première non trouvée'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0',
            'stock_actuel' => 'required|integer|min:0',
        ]);

        $rawMaterial->update([
            'nom' => $request->nom,
            'prix_unitaire' => $request->prix_unitaire,
            'stock_min' => $request->stock_min,
            'stock_actuel' => $request->stock_actuel,
        ]);

        return response()->json([
            'message' => 'Matière première modifiée avec succès',
            'raw_material' => $rawMaterial
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete_rawMaterial');

        $rawMaterial = RawMaterial::find($id);

        if (!$rawMaterial) {
            return response()->json([
                'message' => 'Matière première non trouvée'
            ], 404);
        }

        $rawMaterial->delete();

        return response()->json([
            'message' => 'Matière première supprimée avec succès'
        ]);
    }
}