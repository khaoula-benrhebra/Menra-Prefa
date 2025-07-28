<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('create_order');
        
        $commandes = $request->user()->commandes()
                            ->with(['products' => function($query) {
                                $query->select('products.id', 'products.nom', 'products.prix_unitaire');
                            }])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $commandes
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create_order');
        
        $validator = Validator::make($request->all(), [
            'adresse' => 'required|string',
            'produits' => 'required|array|min:1',
            'produits.*.product_id' => 'required|exists:products,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'moyen_paiement' => 'required|in:espece,virement,cheque',
            'commentaire' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mise à jour de l'adresse utilisateur
        $request->user()->update(['adresse' => $request->adresse]);

        // Vérification du stock et détermination du statut
        $total = 0;
        $productsToUpdate = [];
        $allProductsAvailable = true;
        
        foreach ($request->produits as $produit) {
            $product = Product::findOrFail($produit['product_id']);
            
            if ($product->stock_actuel < $produit['quantite']) {
                $allProductsAvailable = false;
            }
            
            $total += $product->prix_unitaire * $produit['quantite'];
            $productsToUpdate[] = [
                'product' => $product,
                'quantite' => $produit['quantite']
            ];
        }

        
        $statut = $allProductsAvailable ? 'terminee' : 'en_attente';

        DB::beginTransaction();
        
        try {
            // Création de la commande avec le statut approprié
            $commande = Commande::create([
                'user_id' => $request->user()->id,
                'date_commande' => now(),
                'statut' => $statut,
                'total' => $total,
                'commentaire' => $request->commentaire,
                'moyen_paiement' => $request->moyen_paiement
            ]);

            // Ajout des produits à la commande ET mise à jour du stock si disponible
            foreach ($request->produits as $produit) {
                $product = Product::findOrFail($produit['product_id']);
                
                // Attacher le produit à la commande
                $commande->products()->attach($produit['product_id'], [
                    'quantite' => $produit['quantite']
                ]);
                
                // Soustraire la quantité du stock actuel seulement si le produit est disponible
                if ($product->stock_actuel >= $produit['quantite']) {
                    $product->decrement('stock_actuel', $produit['quantite']);
                }
            }

            DB::commit();

            $commande->load(['products' => function($query) {
                $query->select('products.id', 'products.nom', 'products.prix_unitaire');
            }]);

            $message = $statut === 'terminee' 
                ? 'Commande créée avec succès et stock mis à jour' 
                : 'Commande créée avec succès. Certains produits ne sont pas disponibles en stock suffisant, la commande est en attente.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $commande
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $this->authorize('create_order');
        
        $commande = $request->user()->commandes()
                           ->with(['products' => function($query) {
                               $query->select('products.id', 'products.nom', 'products.prix_unitaire');
                           }])
                           ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $commande
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update_order');
        
        $commande = $request->user()->commandes()->findOrFail($id);

        if (!$commande->isModifiable()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut plus être modifiée car son statut est: ' . $commande->statut
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'produits' => 'required|array|min:1',
            'produits.*.product_id' => 'required|exists:products,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'moyen_paiement' => 'sometimes|in:espece,virement,cheque',
            'commentaire' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Récupérer les anciens produits pour restaurer le stock
            $oldProducts = $commande->products;
            
            // Restaurer le stock des anciens produits (seulement si la commande était terminée)
            if ($commande->statut === 'terminee') {
                foreach ($oldProducts as $oldProduct) {
                    $oldProduct->increment('stock_actuel', $oldProduct->pivot->quantite);
                }
            }

            // Vérification du stock pour les nouveaux produits et détermination du statut
            $total = 0;
            $allProductsAvailable = true;
            
            foreach ($request->produits as $produit) {
                $product = Product::findOrFail($produit['product_id']);
                
                if ($product->stock_actuel < $produit['quantite']) {
                    $allProductsAvailable = false;
                }
                
                $total += $product->prix_unitaire * $produit['quantite'];
            }

            
            $nouveauStatut = $allProductsAvailable ? 'terminee' : 'en_attente';

            // Mise à jour de la commande
            $commande->update([
                'total' => $total,
                'statut' => $nouveauStatut,
                'commentaire' => $request->commentaire ?? $commande->commentaire,
                'moyen_paiement' => $request->moyen_paiement ?? $commande->moyen_paiement
            ]);

            // Suppression des anciennes lignes de commande
            $commande->products()->detach();

            // Ajout des nouveaux produits et mise à jour du stock
            foreach ($request->produits as $produit) {
                $product = Product::findOrFail($produit['product_id']);
                
                $commande->products()->attach($produit['product_id'], [
                    'quantite' => $produit['quantite']
                ]);
                
                // Soustraire la quantité du stock actuel seulement si disponible
                if ($product->stock_actuel >= $produit['quantite']) {
                    $product->decrement('stock_actuel', $produit['quantite']);
                }
            }

            DB::commit();

            $commande->load(['products' => function($query) {
                $query->select('products.id', 'products.nom', 'products.prix_unitaire');
            }]);

            $message = $nouveauStatut === 'terminee' 
                ? 'Commande modifiée avec succès et stock mis à jour' 
                : 'Commande modifiée avec succès. Certains produits ne sont pas disponibles en stock suffisant, la commande est en attente.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $commande
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('Cancel_order');
        
        $commande = $request->user()->commandes()->findOrFail($id);

        if (!$commande->isModifiable()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut plus être supprimée car son statut est: ' . $commande->statut
            ], 403);
        }

        DB::beginTransaction();
        
        try {
            // Restaurer le stock des produits avant suppression (seulement si la commande était terminée)
            if ($commande->statut === 'terminee') {
                $products = $commande->products;
                foreach ($products as $product) {
                    $product->increment('stock_actuel', $product->pivot->quantite);
                }
            }

            $commande->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande supprimée avec succès et stock restauré'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la commande: ' . $e->getMessage()
            ], 500);
        }
    }
}