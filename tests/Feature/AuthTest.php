<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_utilisateur_peut_s_inscrire(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Jean Dupont',
            'email'                 => 'jean@exemple.com',
            'password'              => 'motdepasse123!',
            'password_confirmation' => 'motdepasse123!',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'jean@exemple.com']);
    }

    #[Test]
    public function un_utilisateur_peut_se_connecter(): void
    {
        $utilisateur = User::factory()->create([
            'password' => bcrypt('motdepasse123!'),
        ]);

        $response = $this->post('/login', [
            'email'    => $utilisateur->email,
            'password' => 'motdepasse123!',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($utilisateur);
    }

    #[Test]
    public function un_utilisateur_peut_se_deconnecter(): void
    {
        $utilisateur = User::factory()->create();

        $this->actingAs($utilisateur)
             ->post('/logout')
             ->assertRedirect('/');

        $this->assertGuest();
    }

    #[Test]
    public function un_utilisateur_non_authentifie_est_redirige_vers_la_connexion(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    #[Test]
    public function des_identifiants_invalides_sont_rejetes(): void
    {
        $utilisateur = User::factory()->create();

        $this->post('/login', [
            'email'    => $utilisateur->email,
            'password' => 'mauvais_mot_de_passe',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }
}
