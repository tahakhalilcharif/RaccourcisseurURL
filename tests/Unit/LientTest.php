<?php

namespace Tests\Unit;

use App\Models\Lien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LienTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function la_generation_du_code_court_est_unique_et_correcte(): void
    {
        $code = Lien::genererCodeCourt();

        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{6}$/', $code);
    }

    #[Test]
    public function deux_codes_courts_generes_sont_differents(): void
    {
        $code1 = Lien::genererCodeCourt();
        $code2 = Lien::genererCodeCourt();

        $this->assertNotNull($code1);
        $this->assertNotNull($code2);
    }

    #[Test]
    public function le_lien_appartient_a_un_utilisateur(): void
    {
        $utilisateur = User::factory()->create();

        $lien = Lien::create([
            'utilisateur_id'  => $utilisateur->id,
            'url_originale'   => 'https://example.com',
            'code_court'      => Lien::genererCodeCourt(),
        ]);

        $this->assertInstanceOf(User::class, $lien->utilisateur);
        $this->assertEquals($utilisateur->id, $lien->utilisateur->id);
    }

    #[Test]
    public function utilisateur_possede_des_liens(): void
    {
        $utilisateur = User::factory()->create();

        Lien::create([
            'utilisateur_id' => $utilisateur->id,
            'url_originale'  => 'https://example.com',
            'code_court'     => Lien::genererCodeCourt(),
        ]);

        $this->assertCount(1, $utilisateur->liens);
    }
}
