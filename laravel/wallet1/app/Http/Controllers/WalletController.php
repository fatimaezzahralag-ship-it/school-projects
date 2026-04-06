<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Consulter le solde - GET /api/wallet
     */
    public function show()
    {
        $user = auth('api')->user();

        return response()->json([
            'user_id' => $user->id,
            'email' => $user->email,
            'solde' => $user->solde,
        ], 200);
    }

    /**
     * Dépenser des points - POST /api/wallet/spend
     */
    public function spend(Request $request)
    {
        $request->validate([
            'montant' => 'required|integer|min:10',
        ], [
            'montant.required' => 'Le montant est requis',
            'montant.integer' => 'Le montant doit être un entier',
            'montant.min' => 'Le montant minimum est de 10 points',
        ]);

        $user = auth('api')->user();
        $montant = $request->montant;

    
        if ($user->solde < $montant) {
            return response()->json([
                'message' => 'Solde insuffisant',
                'solde_actuel' => $user->solde,
                'montant_demandé' => $montant,
            ], 422);
        }


        $user->solde -= $montant;
        $user->save();

        return response()->json([
            'message' => 'Dépense enregistrée',
            'montant_dépensé' => $montant,
            'nouveau_solde' => $user->solde,
        ], 200);
    }
}