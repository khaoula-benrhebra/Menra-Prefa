<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
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
        ]);

        // Récupérer le rôle Client
        $clientRole = Role::where('nom', 'Client')->first();
        if (!$clientRole) {
            return response()->json(['message' => 'Erreur de configuration des rôles'], 500);
        }

        // Créer l'utilisateur (email non vérifié)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role_id' => $clientRole->id,
            'email_verified_at' => null, // Email non vérifié
        ]);

        // Envoyer l'email de vérification
        $user->notify(new EmailVerificationNotification());

        return response()->json([
            'message' => 'Inscription réussie. Un email de vérification a été envoyé à votre adresse.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role->nom,
                'email_verified' => false,
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

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Vérifier si l'email est vérifié pour les clients
        if ($user->role->nom === 'Client' && !$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Veuillez vérifier votre adresse email avant de vous connecter.',
                'email_verified' => false,
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
                'email_verified' => $user->hasVerifiedEmail(),
            ],
            'token' => $token,
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Lien de vérification invalide'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié'], 200);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'Email vérifié avec succès. Vous pouvez maintenant vous connecter.',
            'email_verified' => true,
        ]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié'], 200);
        }

        $user->notify(new EmailVerificationNotification());

        return response()->json(['message' => 'Email de vérification renvoyé']);
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
                'email_verified' => $user->hasVerifiedEmail(),
            ],
        ]);
    }
}