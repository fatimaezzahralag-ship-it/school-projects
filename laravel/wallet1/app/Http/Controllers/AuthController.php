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
     * Inscription - POST /api/auth/register
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
            'role' => 'user', 
            'solde' => 0,    
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
        ], 201);
    }

    /**
     * Connexion - POST /api/auth/login
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
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Impossible de créer le token',
            ], 500);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => auth('api')->user(),
        ], 200);
    }

    /**
     * Profil utilisateur - GET /api/auth/me
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
     * Déconnexion - POST /api/auth/logout
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