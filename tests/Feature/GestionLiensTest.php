<?php

namespace Tests\Feature;

use App\Models\Lien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GestionLiensTest extends TestCase
{
    use RefreshDatabase;

    private User $utilisateur;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utilisateur = User::factory()->create();
    }

    #[Test]
    public function un_utilisateur_peut_voir_son_tableau_de_bord(): void
    {
        $this->actingAs($this->utilisateur)
             ->get('/dashboard')
             ->assertOk()
             ->assertViewIs('dashboard.index');
    }

    #[Test]
    public function un_utilisateur_peut_creer_un_lien(): void
    {
        $response = $this->actingAs($this->utilisateur)
             ->post('/dashboard', [
                 'url_originale' => 'https://www.exemple.com/une-page-tres-longue',
             ]);

        $response->assertRedirect(route('dashboard.index'));
        $response->assertSessionHas('succes');

        $this->assertDatabaseHas('liens', [
            'utilisateur_id' => $this->utilisateur->id,
            'url_originale'  => 'https://www.exemple.com/une-page-tres-longue',
        ]);

        $lien = Lien::where('utilisateur_id', $this->utilisateur->id)->first();
        $this->assertEquals(8, strlen($lien->code_court));
    }

    #[Test]
    public function une_url_invalide_est_rejetee(): void
    {
        $this->actingAs($this->utilisateur)
             ->post('/dashboard', ['url_originale' => 'pas-une-url'])
             ->assertSessionHasErrors('url_originale');
    }

    #[Test]
    public function un_utilisateur_peut_modifier_son_lien(): void
    {
        $lien = Lien::create([
            'utilisateur_id' => $this->utilisateur->id,
            'url_originale'  => 'https://ancienne.com',
            'code_court'     => 'abc123',
        ]);

        $response = $this->actingAs($this->utilisateur)
             ->put("/dashboard/{$lien->id}", [
                 'url_originale' => 'https://nouvelle.com',
             ]);

        $response->assertRedirect(route('dashboard.index'));
        $response->assertSessionHas('succes');
        $this->assertDatabaseHas('liens', ['url_originale' => 'https://nouvelle.com']);
    }

    #[Test]
    public function un_utilisateur_peut_supprimer_son_lien(): void
    {
        $lien = Lien::create([
            'utilisateur_id' => $this->utilisateur->id,
            'url_originale'  => 'https://exemple.com',
            'code_court'     => 'del123',
        ]);

        $response = $this->actingAs($this->utilisateur)
             ->delete("/dashboard/{$lien->id}");

        $response->assertRedirect(route('dashboard.index'));
        $this->assertDatabaseMissing('liens', ['id' => $lien->id]);
    }

    #[Test]
    public function un_utilisateur_ne_peut_pas_modifier_le_lien_d_un_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $lien = Lien::create([
            'utilisateur_id' => $autreUtilisateur->id,
            'url_originale'  => 'https://exemple.com',
            'code_court'     => 'zzz999',
        ]);

        $this->actingAs($this->utilisateur)
             ->put("/dashboard/{$lien->id}", ['url_originale' => 'https://hack.com'])
             ->assertForbidden();
    }

    #[Test]
    public function un_utilisateur_ne_peut_pas_supprimer_le_lien_d_un_autre(): void
    {
        $autreUtilisateur = User::factory()->create();
        $lien = Lien::create([
            'utilisateur_id' => $autreUtilisateur->id,
            'url_originale'  => 'https://exemple.com',
            'code_court'     => 'yyy888',
        ]);

        $this->actingAs($this->utilisateur)
             ->delete("/dashboard/{$lien->id}")
             ->assertForbidden();
    }

    #[Test]
    public function le_tableau_de_bord_est_pagine(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Lien::create([
                'utilisateur_id' => $this->utilisateur->id,
                'url_originale'  => "https://exemple.com/page-{$i}",
                'code_court'     => Lien::genererCodeCourt(),
            ]);
        }

        $response = $this->actingAs($this->utilisateur)
             ->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('liens', function ($liens) {
            return $liens->count() === 10 && $liens->total() === 15;
        });
    }
}
