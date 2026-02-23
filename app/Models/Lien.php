<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lien extends Model
{
    use HasFactory;

    protected $table = 'liens';

    protected $fillable = [
        'utilisateur_id',
        'url_originale',
        'code_court',
        'nombre_visites',
        'derniere_visite_le'        
    ];

    // on doit caster le champ derniere_visite_le en datetime pour pouvoir l'utiliser facilement dans le code
    protected function casts(): array
    {
        return [
            'derniere_visite_le' => 'datetime',
        ];
    }

    // association entre le lien et son utilisateur
    public function utilisateur() :BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
    
    // generer un code court unique pour chaque lien
    public static function genererCodeCourt(): string
    {
        do {
            $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
        } while (self::where('code_court', $code)->exists()); // on verifie que le code genere n'existe pas deja dans la bdd

        return $code;
    }
}
