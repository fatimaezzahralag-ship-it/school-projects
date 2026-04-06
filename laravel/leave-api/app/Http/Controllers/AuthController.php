<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * 📝 INSCRIPTION - POST /api/auth/register
     * Créer un nouvel utilisateur (employe par défaut)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employe',        // Rôle par défaut
            'solde_conges' => 0,        // Solde initial = 0
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
        ], 201);
    }

    /**
     * 🔑 CONNEXION - POST /api/auth/login
     * Retourner le token JWT pour accéder à l'API
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Identifiants invalides',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Impossible de générer le token',
            ], 500);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => auth('api')->user(),
        ], 200);
    }

    /**
     * 👤 PROFIL UTILISATEUR - GET /api/auth/me
     * Retourner les infos de l'utilisateur authentifié
     */
    public function me()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié',
            ], 401);
        }

        return response()->json([
            'user' => $user,
        ], 200);
    }

    /**
     * 🚪 DÉCONNEXION - POST /api/auth/logout
     * Invalider le token JWT
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return response()->json([
                'message' => 'Déconnexion réussie',
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion',
            ], 500);
        }
    }
}