<?php

namespace App\Http\Controllers;

use App\Models\Lien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LienController extends Controller
{

    // affiche la liste paginée des lien de user connecté
    public function index(): View
    {
        $liens = auth()->user()->liens()
            ->orderByDesc('created_at')
            ->paginate(10);
        
            Log::info('Liens récupérés pour l\'utilisateur ' . auth()->id(), ['liens_count' => $liens->count()]);

        return view('dashboard.index', compact('liens'));
    }

    // affiche la page de création d'un lien
    public function create(): View
    {
        return view('dashboard.create');
    }

    // enregistre un nouveau lien pour l'utilisateur connecté
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'url_originale' => ['required', 'url', 'max:2048'],
        ], [
            'url_originale.required' => "L'URL est obligatoire.",
            'url_originale.url'      => "L'URL saisie n'est pas valide.",
            'url_originale.max'      => "L'URL ne doit pas dépasser 2048 caractères.",
        ]);

        auth()->user()->liens()->create([
            'url_originale' => $request->url_originale,
            'code_court'    => Lien::genererCodeCourt(),
        ]);

        return redirect()->route('dashboard.index')
            ->with('succes', 'Lien créé avec succès !');
    }

    // affiche la page d'editer un lien
    public function edit(Lien $lien): View
    {
        $this->authorise($lien);
        return view('dashboard.edit', compact('lien'));
    }

    // mise a jour d'un lien
    public function update(Request $request, Lien $lien): RedirectResponse
    {
        $this->authorise($lien);

        $request->validate([
            'url_originale' => ['required', 'url', 'max:2048'],
        ], [
            'url_originale.required' => "L'URL est obligatoire.",
            'url_originale.url'      => "L'URL saisie n'est pas valide.",
            'url_originale.max'      => "L'URL ne doit pas dépasser 2048 caractères.",
        ]);

        $lien->update(['url_originale' => $request->url_originale]);

        return redirect()
                ->route('dashboard.index')
                ->with('succes', 'Lien mis à jour avec succès !');
    }

    // suppression d'un lien
    public function destroy(Lien $lien): RedirectResponse
    {
        $this->authorise($lien);
        $lien->delete();

        return redirect()
                ->route('dashboard.index')
                ->with('succes', 'Lien supprimé avec succès !');
    }

    // verifie que le lien appartient au user connecté
    private function authorise(Lien $lien): void
    {
        abort_if($lien->utilisateur_id !== auth()->id(), 403, 'Action non autorisée.');
    }


}
