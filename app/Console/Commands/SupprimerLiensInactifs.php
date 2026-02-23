<?php

namespace App\Console\Commands;

use App\Models\Lien;
use Illuminate\Console\Command;

class SupprimerLiensInactifs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liens:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les liens non utilisés depuis plus de 3 mois.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limite = now()->subMonths(3);

        $nbSupprimes = Lien::where(function ($query) use ($limite) {
            $query->where('derniere_visite_le', '<', $limite)
                  ->orWhere(function ($q) use ($limite) {
                      $q->whereNull('derniere_visite_le')
                        ->where('created_at', '<', $limite);
                  });
        })->delete();

        $this->info("Purge terminée : {$nbSupprimes} lien(s) supprimé(s).");

        return self::SUCCESS;
    }
}
