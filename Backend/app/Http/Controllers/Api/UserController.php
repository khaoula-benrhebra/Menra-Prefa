<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Notifications\UserCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Créer un nouvel utilisateur (réservé aux admins)
     */
    public function store(Request $request)
    {
        // Vérifier les permissions
        $this->authorize('create_users');

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role' => ['required', 'string', Rule::in(['Responsable production', 'Agent commercial'])],
        ]);

        // Récupérer le rôle demandé
        $role = Role::where('nom', $request->role)->first();
        if (!$role) {
            return response()->json([
                'message' => 'Rôle invalide. Les rôles autorisés sont : Responsable production, Agent commercial'
            ], 400);
        }

        // Créer l'utilisateur avec email vérifié par défaut
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password, // Sera hashé automatiquement grâce au cast
            'role_id' => $role->id,
            'email_verified_at' => now(), // Email vérifié par défaut
        ]);

        // Envoyer l'email avec les identifiants
        $user->notify(new UserCreatedNotification($request->password));

        return response()->json([
            'message' => 'Utilisateur créé avec succès. Un email avec les identifiants a été envoyé.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->nom,
                'email_verified' => true,
                'created_at' => $user->created_at,
            ],
        ], 201);
    }

    /**
     * Lister tous les utilisateurs (optionnel pour l'admin)
     */
    public function index(Request $request)
    {
        $this->authorize('create_users'); // Même permission pour voir les utilisateurs

        $users = User::with('role')->get();

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role->nom,
                    'email_verified' => $user->hasVerifiedEmail(),
                    'created_at' => $user->created_at,
                ];
            })
        ]);
    }
}