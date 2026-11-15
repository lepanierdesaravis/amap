<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\BuildsAmap;
use Tests\TestCase;

class AccessTest extends TestCase
{
    use BuildsAmap, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        $this->seed(PageSeeder::class);
    }

    #[Test]
    public function les_pages_publiques_s_affichent(): void
    {
        $producer = $this->producer(attributes: ['name' => 'La Ferme du Coteau']);

        foreach (['/', '/l-amap', '/producteurs', '/producteurs/'.$producer->slug, '/calendrier', '/adherer', '/contact', '/connexion', '/inscription', '/infos/'.Page::first()->slug] as $url) {
            $this->get($url)->assertOk();
        }
    }

    #[Test]
    public function une_fiche_non_publiee_reste_cachee(): void
    {
        $producer = $this->producer(attributes: ['is_published' => false]);

        $this->get('/producteurs/'.$producer->slug)->assertNotFound();
    }

    #[Test]
    public function les_espaces_prives_demandent_une_connexion(): void
    {
        foreach (['/mon-espace', '/mon-espace/contrats', '/espace-producteur', '/gestion'] as $url) {
            $this->get($url)->assertRedirect();
            $this->assertGuest();
        }
    }

    #[Test]
    public function l_adherent_accede_a_son_espace_mais_pas_a_la_gestion(): void
    {
        $member = $this->member();

        foreach (['/mon-espace', '/mon-espace/contrats', '/mon-espace/commandes-groupees', '/mon-espace/calendrier', '/mon-espace/paiements', '/mon-espace/compte'] as $url) {
            $this->actingAs($member)->get($url)->assertOk();
        }
        $this->actingAs($member)->get('/espace-producteur')->assertForbidden();
        $this->actingAs($member)->get('/gestion')->assertForbidden();
    }

    #[Test]
    public function le_referent_ne_gere_que_ses_producteurs(): void
    {
        $referent = $this->userWithRole(Role::Referent);
        $other = $this->userWithRole(Role::Referent);
        $mine = $this->producer($referent);
        $theirs = $this->producer($other);

        $this->assertTrue($referent->can('update', $mine));
        $this->assertFalse($referent->can('update', $theirs));
        $this->assertFalse($this->member()->can('create', $mine::class));

        $this->actingAs($referent)->get('/gestion/producteurs')->assertOk()->assertSee($mine->name)->assertDontSee($theirs->name);
        $this->actingAs($referent)->get("/gestion/producteurs/{$theirs->slug}/modifier")->assertNotFound();
    }

    #[Test]
    public function l_administrateur_a_tous_les_droits(): void
    {
        $admin = $this->userWithRole(Role::Admin);
        $producer = $this->producer($this->userWithRole(Role::Referent));

        $this->assertTrue($admin->can('update', $producer));
        $this->actingAs($admin)->get("/gestion/producteurs/{$producer->slug}/modifier")->assertOk();
    }

    #[Test]
    public function toutes_les_pages_de_gestion_s_affichent(): void
    {
        $admin = $this->userWithRole(Role::Admin);
        $producer = $this->producer($admin);
        $this->product($producer);
        $distribution = $this->distributions($producer, 1)->first();
        $offer = $this->contractOffer($producer);
        $purchase = $this->groupPurchase($producer, $distribution);

        $urls = ['', '/producteurs', '/producteurs/ajouter', "/producteurs/{$producer->slug}/modifier",
            '/offres-de-contrat', '/offres-de-contrat/ajouter', "/offres-de-contrat/{$offer->id}/modifier",
            '/commandes-groupees', '/commandes-groupees/nouvelle', "/commandes-groupees/{$purchase->slug}/modifier",
            '/distributions', "/distributions/{$distribution->id}/modifier", '/paiements',
            '/adherents', "/adherents/{$admin->id}/modifier", '/pages', '/pages/'.Page::first()->id.'/modifier'];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get('/gestion'.$url)->assertOk();
        }
    }

    #[Test]
    public function la_connexion_de_demonstration_n_existe_pas_hors_local(): void
    {
        $this->get('/demo/connexion/admin@exemple.fr')->assertNotFound();
    }
}
