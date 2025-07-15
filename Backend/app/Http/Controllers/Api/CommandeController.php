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

        // Vérification du stock
        $stockErrors = [];
        $total = 0;
        $productsToUpdate = [];
        
        foreach ($request->produits as $produit) {
            $product = Product::findOrFail($produit['product_id']);
            
            if ($product->stock_actuel < $produit['quantite']) {
                $stockErrors[] = [
                    'product_id' => $product->id,
                    'nom' => $product->nom,
                    'quantite_demandee' => $produit['quantite'],
                    'stock_disponible' => $product->stock_actuel
                ];
            } else {
                $total += $product->prix_unitaire * $produit['quantite'];
                $productsToUpdate[] = [
                    'product' => $product,
                    'quantite' => $produit['quantite']
                ];
            }
        }

        if (!empty($stockErrors)) {
            $message = "La quantité demandée n'est pas disponible actuellement. ";
            foreach ($stockErrors as $error) {
                $message .= "Le stock disponible pour le produit {$error['nom']} est de {$error['stock_disponible']} unités. ";
            }
            $message .= "Nous travaillons sur le réapprovisionnement dans les 2 jours suivants.";
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'stock_errors' => $stockErrors
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            // Création de la commande
            $commande = Commande::create([
                'user_id' => $request->user()->id,
                'date_commande' => now(),
                'statut' => 'en_attente',
                'total' => $total,
                'commentaire' => $request->commentaire,
                'moyen_paiement' => $request->moyen_paiement
            ]);

            // Ajout des produits à la commande ET mise à jour du stock
            foreach ($request->produits as $produit) {
                $product = Product::findOrFail($produit['product_id']);
                
                // Attacher le produit à la commande
                $commande->products()->attach($produit['product_id'], [
                    'quantite' => $produit['quantite']
                ]);
                
                // Soustraire la quantité du stock actuel
                $product->decrement('stock_actuel', $produit['quantite']);
            }

            DB::commit();

            $commande->load(['products' => function($query) {
                $query->select('products.id', 'products.nom', 'products.prix_unitaire');
            }]);

            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès et stock mis à jour',
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
            
            // Restaurer le stock des anciens produits
            foreach ($oldProducts as $oldProduct) {
                $oldProduct->increment('stock_actuel', $oldProduct->pivot->quantite);
            }

            // Vérification du stock pour les nouveaux produits
            $stockErrors = [];
            $total = 0;
            
            foreach ($request->produits as $produit) {
                $product = Product::findOrFail($produit['product_id']);
                
                if ($product->stock_actuel < $produit['quantite']) {
                    $stockErrors[] = [
                        'product_id' => $product->id,
                        'nom' => $product->nom,
                        'quantite_demandee' => $produit['quantite'],
                        'stock_disponible' => $product->stock_actuel
                    ];
                } else {
                    $total += $product->prix_unitaire * $produit['quantite'];
                }
            }

            if (!empty($stockErrors)) {
                // Restaurer le stock des anciens produits si erreur
                foreach ($oldProducts as $oldProduct) {
                    $oldProduct->decrement('stock_actuel', $oldProduct->pivot->quantite);
                }
                
                $message = "La quantité demandée n'est pas disponible actuellement. ";
                foreach ($stockErrors as $error) {
                    $message .= "Le stock disponible pour le produit {$error['nom']} est de {$error['stock_disponible']} unités. ";
                }
                $message .= "Nous travaillons sur le réapprovisionnement dans les 2 jours suivants.";
                
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'stock_errors' => $stockErrors
                ], 400);
            }

            // Mise à jour de la commande
            $commande->update([
                'total' => $total,
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
                
                // Soustraire la quantité du stock actuel
                $product->decrement('stock_actuel', $produit['quantite']);
            }

            DB::commit();

            $commande->load(['products' => function($query) {
                $query->select('products.id', 'products.nom', 'products.prix_unitaire');
            }]);

            return response()->json([
                'success' => true,
                'message' => 'Commande modifiée avec succès et stock mis à jour',
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
            // Restaurer le stock des produits avant suppression
            $products = $commande->products;
            foreach ($products as $product) {
                $product->increment('stock_actuel', $product->pivot->quantite);
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