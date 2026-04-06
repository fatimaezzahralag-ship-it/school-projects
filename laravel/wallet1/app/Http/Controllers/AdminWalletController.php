<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    /**
     * Créditer un compte utilisateur - POST /api/admin/wallet/{user}/credit
     */
    public function credit(Request $request, User $user)
    {
        $request->validate([
            'montant' => 'required|integer|min:1',
        ], [
            'montant.required' => 'Le montant est requis',
            'montant.integer' => 'Le montant doit être un entier',
            'montant.min' => 'Le montant doit être strictement positif',
        ]);

        $montant = $request->montant;

        $user->solde += $montant;
        $user->save();

        return response()->json([
            'message' => 'Crédit effectué',
            'utilisateur_id' => $user->id,
            'utilisateur_email' => $user->email,
            'montant_crédité' => $montant,
            'nouveau_solde' => $user->solde,
        ], 200);
    }

    /**
     * Débiter un compte utilisateur - POST /api/admin/wallet/{user}/debit
     */
    public function debit(Request $request, User $user)
    {
        $request->validate([
            'montant' => 'required|integer|min:1',
        ], [
            'montant.required' => 'Le montant est requis',
            'montant.integer' => 'Le montant doit être un entier',
            'montant.min' => 'Le montant doit être strictement positif',
        ]);

        $montant = $request->montant;

        if ($user->solde < $montant) {
            return response()->json([
                'message' => 'Solde insuffisant pour ce débit',
                'solde_actuel' => $user->solde,
                'montant_demandé' => $montant,
            ], 422);
        }

        
        $user->solde -= $montant;
        $user->save();

        return response()->json([
            'message' => 'Débit effectué',
            'utilisateur_id' => $user->id,
            'utilisateur_email' => $user->email,
            'montant_débité' => $montant,
            'nouveau_solde' => $user->solde,
        ], 200);
    }
}