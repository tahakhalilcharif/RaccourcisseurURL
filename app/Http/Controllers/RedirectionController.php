<?php

namespace App\Http\Controllers;

use App\Models\Lien;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;

class RedirectionController extends Controller
{
    // rediriger vers l'url originale en fonction du code court
    public function redirect(string $code): RedirectResponse
    {
        $lien = Lien::where('code_court', $code)->first();

        if (! $lien) {
            return redirect()->route('lien-expire');
        }

        $lien->increment('nombre_visites');
        $lien->update(['derniere_visite_le' => now()]);

        return redirect()->away($lien->url_originale);
    }
}
