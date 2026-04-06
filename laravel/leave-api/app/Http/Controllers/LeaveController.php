<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * 📊 CONSULTER LE SOLDE - GET /api/leave
     * Retourner le solde de congés de l'utilisateur connecté
     */
    public function show()
    {
        $user = auth('api')->user();

        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'solde_conges' => $user->solde_conges,
            'role' => $user->role,
        ], 200);
    }

    /**
     * 📋 DEMANDER DES CONGÉS - POST /api/leave/request
     * Soumettre une demande de congé (consomme des jours)
     */
    public function request(Request $request)
    {
    
        $request->validate([
            'jours' => 'required|integer|min:1',  
        ], [
            'jours.required' => 'Le nombre de jours est requis',
            'jours.integer' => 'Le nombre de jours doit être un entier',
            'jours.min' => 'Le nombre de jours minimum est de 1',
        ]);

        $user = auth('api')->user();
        $jours = $request->jours;

        if ($user->solde_conges < $jours) {
            return response()->json([
                'message' => 'Solde de congés insuffisant',
                'solde_actuel' => $user->solde_conges,
                'jours_demandés' => $jours,
            ], 422);
        }

        $user->solde_conges -= $jours;
        $user->save();

        return response()->json([
            'message' => 'Demande de congé enregistrée',
            'jours_consommés' => $jours,
            'nouveau_solde' => $user->solde_conges,
        ], 200);
    }
}