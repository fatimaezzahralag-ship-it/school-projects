<?php

namespace App\Http\Controllers;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // GET /api/loans
    public function index() {
        return response()->json([
            'message' => 'Liste des emprunts récupérée',
            'data' => Loan::all()
        ], 200);
    }
    // POST /api/loans 
    public function store(Request $request) {
        $validated = $request->validate([
            'borrower_name' => 'required|string',
            'borrower_email' => 'required|email',
            'book_title' => 'required|string',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date',
        ]);

        $loan = Loan::create($validated);

        return response()->json([
            'message' => 'Emprunt créé avec succès',
            'data' => $loan
        ], 201);
    }

    // GET /api/loans/{id}
    public function show($id) {
        $loan = Loan::find($id);
        if (!$loan) return response()->json(['message' => 'Emprunt non trouvé'], 404);

        return response()->json(['message' => 'Détails de l\'emprunt', 'data' => $loan], 200);
    }

    // PUT /api/loans/{id}
    public function update(Request $request, $id) {
        $loan = Loan::find($id);
        if (!$loan) return response()->json(['message' => 'Emprunt non trouvé'], 404);

        $loan->update($request->all());
        return response()->json(['message' => 'Emprunt mis à jour', 'data' => $loan], 200);
    }

    // PATCH /api/loans/{id}/return
    public function returnBook($id) {
        $loan = Loan::find($id);
        if (!$loan) return response()->json(['message' => 'Emprunt non trouvé'], 404);

        $loan->update(['returned' => true, 'status' => 'returned']);
        return response()->json(['message' => 'Livre marqué comme rendu', 'data' => $loan], 200);
    }

    // DELETE /api/loans/{id}
    public function destroy($id) {
        $loan = Loan::find($id);
        if (!$loan) return response()->json(['message' => 'Emprunt non trouvé'], 404);

        $loan->delete();
        return response()->json(['message' => 'Emprunt supprimé'], 204);
    }
}
