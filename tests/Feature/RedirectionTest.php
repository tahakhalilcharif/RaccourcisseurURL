<?php

namespace Tests\Feature;

use App\Models\Lien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RedirectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function l_url_courte_redirige_vers_l_originale(): void
    {
        $utilisateur = User::factory()->create();

        Lien::create([
            'utilisateur_id' => $utilisateur->id,
            'url_originale'  => 'https://www.exemple.com/page-destination',
            'code_court'     => 'abc123',
        ]);

        $this->get('/abc123')
             ->assertRedirect('https://www.exemple.com/page-destination');
    }

    #[Test]
    public function le_nombre_de_visites_s_incremente_a_la_redirection(): void
    {
        $utilisateur = User::factory()->create();

        Lien::create([
            'utilisateur_id' => $utilisateur->id,
            'url_originale'  => 'https://www.exemple.com',
            'code_court'     => 'vis123',
            'nombre_visites' => 0,
        ]);

        $this->get('/vis123');

        $this->assertDatabaseHas('liens', [
            'code_court'     => 'vis123',
            'nombre_visites' => 1,
        ]);
    }

    #[Test]
    public function la_date_de_derniere_visite_est_mise_a_jour(): void
    {
        $utilisateur = User::factory()->create();

        $lien = Lien::create([
            'utilisateur_id'     => $utilisateur->id,
            'url_originale'      => 'https://www.exemple.com',
            'code_court'         => 'date12',
            'derniere_visite_le' => null,
        ]);

        $this->get('/date12');

        $lien->refresh();
        $this->assertNotNull($lien->derniere_visite_le);
    }

    #[Test]
    public function un_code_invalide_affiche_la_page_lien_expire(): void
    {
        $this->get('/zzzzzz')
             ->assertRedirect(route('lien-expire'));
    }
}
