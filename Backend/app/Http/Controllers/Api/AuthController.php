<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role_nom' => 'required|string|in:Client,Agent commercial,Responsable production',
        ]);

        // Vérifier que le rôle existe
        $role = Role::where('nom', $request->role_nom)->first();
        if (!$role) {
            return response()->json(['message' => 'Rôle invalide'], 400);
        }

        // Déterminer si l'utilisateur doit être approuvé automatiquement
        $isApproved = $request->role_nom === 'Client';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password, 
            'role_id' => $role->id,
            'is_approved' => $isApproved,
        ]);

        // Si c'est un client, on peut le connecter automatiquement
        if ($isApproved) {
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'Inscription réussie',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role->nom,
                    'is_approved' => $user->is_approved,
                ],
                'token' => $token,
            ], 201);
        }

        return response()->json([
            'message' => 'Inscription réussie. Votre compte est en attente d\'approbation par un administrateur.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->nom,
                'is_approved' => $user->is_approved,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) { // Corrigé: était 'mot_de_passe'
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Vérifier si l'utilisateur est approuvé (sauf pour les clients et admins)
        if (!in_array($user->role->nom, ['Client', 'Admin']) && !$user->is_approved) {
            return response()->json([
                'message' => 'Votre compte est en attente d\'approbation par un administrateur.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->nom,
                'is_approved' => $user->is_approved,
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('role');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->nom,
                'is_approved' => $user->is_approved,
            ],
        ]);
    }
}