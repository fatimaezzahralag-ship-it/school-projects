<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminLeaveController extends Controller
{
    /**
     * ➕ CRÉDITER DES JOURS - POST /api/admin/leave/{user}/credit
     * L'admin ajoute des jours de congé à un employé
     */
    public function credit(Request $request, User $user)
    {
        
        $request->validate([
            'jours' => 'required|integer|min:1',
        ], [
            'jours.required' => 'Le nombre de jours est requis',
            'jours.integer' => 'Le nombre de jours doit être un entier',
            'jours.min' => 'Le nombre de jours doit être strictement positif',
        ]);

        $jours = $request->jours;

        $user->solde_conges += $jours;
        $user->save();

        return response()->json([
            'message' => 'Crédit effectué',
            'employe_id' => $user->id,
            'employe_nom' => $user->name,
            'employe_email' => $user->email,
            'jours_crédités' => $jours,
            'nouveau_solde' => $user->solde_conges,
        ], 200);
    }

    /**
     * ➖ DÉBITER DES JOURS - POST /api/admin/leave/{user}/debit
     * L'admin retire des jours de congé à un employé
     */
    public function debit(Request $request, User $user)
    {

        $request->validate([
            'jours' => 'required|integer|min:1',
        ], [
            'jours.required' => 'Le nombre de jours est requis',
            'jours.integer' => 'Le nombre de jours doit être un entier',
            'jours.min' => 'Le nombre de jours doit être strictement positif',
        ]);

        $jours = $request->jours;

        if ($user->solde_conges < $jours) {
            return response()->json([
                'message' => 'Solde insuffisant pour ce débit',
                'solde_actuel' => $user->solde_conges,
                'jours_demandés' => $jours,
            ], 422);
        }

        $user->solde_conges -= $jours;
        $user->save();

        return response()->json([
            'message' => 'Débit effectué',
            'employe_id' => $user->id,
            'employe_nom' => $user->name,
            'employe_email' => $user->email,
            'jours_débités' => $jours,
            'nouveau_solde' => $user->solde_conges,
        ], 200);
    }
}