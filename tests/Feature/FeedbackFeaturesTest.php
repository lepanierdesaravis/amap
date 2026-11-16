<?php

namespace Tests\Feature;

use App\Enums\Audience;
use App\Enums\Frequency;
use App\Enums\MessageAudience;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Filament\Resources\GroupPurchases\Tables\GroupPurchasesTable;
use App\Filament\Resources\Producers\Pages\EditProducer;
use App\Filament\Resources\Producers\RelationManagers\ProductsRelationManager;
use App\Mail\ContractOfferOpened;
use App\Mail\GroupPurchaseOpened;
use App\Mail\MemberMessageMail;
use App\Models\GroupPurchase;
use App\Models\MemberMessage;
use App\Services\Announcements;
use App\Services\OrderService;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\BuildsAmap;
use Tests\TestCase;

/** Retours de novembre 2026. */
class FeedbackFeaturesTest extends TestCase
{
    use BuildsAmap, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        Mail::fake();
    }

    private function orders(): OrderService
    {
        return app(OrderService::class);
    }

    #[Test]
    public function un_montant_d_engagement_vaut_pour_la_saison(): void
    {
        $producer = $this->producer();
        $amount = $producer->products()->create(['name' => 'Engagement 50 €', 'unit' => 'engagement', 'price_cents' => 5000, 'is_amount' => true]);
        $distributions = $this->distributions($producer, 4);
        $offer = $this->contractOffer($producer);

        // Avec des dates : 50 € au total, pas 50 € par livraison.
        $contract = $this->orders()->saveContract($this->member(), $offer, [[
            'product_id' => $amount->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => $distributions->pluck('id')->all(),
        ]], 1, PaymentMethod::Cheque);
        $this->assertSame(5000, $contract->total_cents);
        $this->assertSame(4, $contract->lines->first()->deliveries()->count());

        // Sans date (boulangerie) : le contrat est accepté, sans livraison.
        $contract = $this->orders()->saveContract($this->member(), $offer, [[
            'product_id' => $amount->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => [],
        ]], 1, PaymentMethod::Cheque);
        $this->assertSame(5000, $contract->total_cents);
        $this->assertSame(0, $contract->lines->first()->deliveries()->count());
    }

    #[Test]
    public function le_nombre_de_contrats_peut_etre_limite(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer);
        $distributions = $this->distributions($producer, 2);
        $offer = $this->contractOffer($producer, ['max_contracts' => 1]);
        $lines = [['product_id' => $product->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => $distributions->pluck('id')->all()]];

        $first = $this->member();
        $this->orders()->saveContract($first, $offer, $lines, 1, PaymentMethod::Cheque);
        // Le premier adhérent garde sa place et peut modifier son contrat.
        $this->orders()->saveContract($first, $offer, $lines, 1, PaymentMethod::Transfer);
        $this->assertSame(0, $offer->remainingPlaces());

        $this->expectException(ValidationException::class);
        $this->orders()->saveContract($this->member(), $offer, $lines, 1, PaymentMethod::Cheque);
    }

    #[Test]
    public function une_commande_liee_a_un_engagement_est_deduite_pour_les_engages_et_reglee_sur_place_pour_les_autres(): void
    {
        $producer = $this->producer();
        $amount = $producer->products()->create(['name' => 'Engagement 100 €', 'unit' => 'engagement', 'price_cents' => 10000, 'is_amount' => true]);
        $distribution = $this->distributions($producer, 1)->first();
        $offer = $this->contractOffer($producer, ['frequencies' => []]);
        $purchase = $this->groupPurchase($producer, $distribution, ['contract_offer_id' => $offer->id]);
        $bread = $purchase->items()->create(['name' => 'Campagnard', 'price_cents' => 680]);

        $engaged = $this->member();
        $contract = $this->orders()->saveContract($engaged, $offer, [['product_id' => $amount->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => []]], 1, PaymentMethod::Cheque);

        $order = $this->orders()->saveGroupOrder($engaged, $purchase, [$bread->id => 2]);
        $this->assertSame($contract->id, $order->contract_id);
        $this->assertSame(0, $order->installmentsDue()->count());
        $this->assertSame(10000 - 1360, $contract->remainingCents());

        $other = $this->orders()->saveGroupOrder($this->member(), $purchase, [$bread->id => 1]);
        $this->assertNull($other->contract_id);
        $this->assertSame(PaymentMethod::OnSite, $other->installmentsDue()->first()->payment_method);
    }

    #[Test]
    public function les_annonces_partent_a_l_ouverture_vers_le_bon_public(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer);
        $distributions = $this->distributions($producer, 2);
        $engaged = $this->member();
        $everyone = $this->member();

        $offer = $this->contractOffer($producer, ['announce_to' => null]);
        $this->orders()->saveContract($engaged, $offer, [['product_id' => $product->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => $distributions->pluck('id')->all()]], 1, PaymentMethod::Cheque);

        $linked = $this->groupPurchase($producer, $distributions[0], ['contract_offer_id' => $offer->id]);
        $next = $this->contractOffer($producer, ['title' => 'Trimestre suivant', 'announce_to' => Audience::CurrentSubscribers]);
        $later = $this->groupPurchase($producer, $distributions[1], ['opens_at' => now()->addDay()]);

        app(Announcements::class)->sendDue();

        Mail::assertQueued(GroupPurchaseOpened::class, fn ($m) => $m->hasTo($engaged->email) && $m->purchase->is($linked));
        Mail::assertNotQueued(GroupPurchaseOpened::class, fn ($m) => $m->hasTo($everyone->email));
        Mail::assertQueued(ContractOfferOpened::class, fn ($m) => $m->hasTo($engaged->email) && $m->offer->is($next));
        Mail::assertNotQueued(ContractOfferOpened::class, fn ($m) => $m->hasTo($everyone->email));
        $this->assertNull($later->fresh()->announced_at);
        $this->assertNotNull($linked->fresh()->announced_at);

        // Une seule annonce par ouverture.
        Mail::fake();
        app(Announcements::class)->sendDue();
        Mail::assertNothingQueued();
    }

    #[Test]
    public function une_commande_se_repete_a_chaque_distribution_avec_les_memes_horaires(): void
    {
        $producer = $this->producer();
        $distributions = $this->distributions($producer, 4);
        $first = $distributions[0]->date;
        // Ouverture le mercredi à 12 h et clôture le jeudi à 18 h avant la distribution du samedi.
        $model = $this->groupPurchase($producer, $distributions[0], [
            'title' => 'Pain', 'opens_at' => $first->copy()->subDays(3)->setTime(12, 0), 'closes_at' => $first->copy()->subDays(2)->setTime(18, 0),
        ]);
        $model->items()->create(['name' => 'Campagnard', 'price_cents' => 680]);

        $this->assertSame(3, GroupPurchasesTable::repeat($model, $distributions[3]->date));
        $this->assertSame(0, GroupPurchasesTable::repeat($model, $distributions[3]->date));

        $copy = GroupPurchase::where('distribution_id', $distributions[2]->id)->first();
        $this->assertEquals($distributions[2]->date->copy()->subDays(3)->setTime(12, 0), $copy->opens_at);
        $this->assertEquals($distributions[2]->date->copy()->subDays(2)->setTime(18, 0), $copy->closes_at);
        $this->assertSame(1, $copy->items()->count());
    }

    #[Test]
    public function une_icone_de_couleur_n_est_utilisee_que_par_un_producteur(): void
    {
        $goats = $this->producer();
        $goats->products()->create(['name' => 'Chèvre frais', 'unit' => 'pièce', 'price_cents' => 300, 'icon' => 'chevre', 'icon_color' => 'noir']);
        $other = $this->producer();

        $this->assertNotNull(ProductsRelationManager::takenBy('chevre', 'noir', $other->products()->make()));
        $this->assertNull(ProductsRelationManager::takenBy('chevre', 'blanc', $other->products()->make()));
        $this->assertNull(ProductsRelationManager::takenBy('chevre', 'noir', $goats->products()->make()));
    }

    #[Test]
    public function le_referent_choisit_une_icone_libre_dans_le_back_office(): void
    {
        $admin = $this->userWithRole(Role::Admin);
        $this->producer()->products()->create(['name' => 'Chèvre frais', 'unit' => 'pièce', 'price_cents' => 300, 'icon' => 'chevre', 'icon_color' => 'noir']);
        $producer = $this->producer();
        $this->actingAs($admin);

        $component = fn () => Livewire::test(ProductsRelationManager::class, ['ownerRecord' => $producer, 'pageClass' => EditProducer::class]);
        $data = ['name' => 'Tomme de chèvre', 'price_cents' => '12,50', 'unit' => 'pièce', 'icon' => 'chevre', 'icon_color' => 'noir'];

        $component()->callAction(TestAction::make('create')->table(), $data)->assertHasFormErrors(['icon']);
        $component()->callAction(TestAction::make('create')->table(), [...$data, 'icon_color' => 'blanc'])->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', ['producer_id' => $producer->id, 'icon' => 'chevre', 'icon_color' => 'blanc', 'price_cents' => 1250]);
    }

    #[Test]
    public function les_formulaires_du_back_office_s_affichent(): void
    {
        $admin = $this->userWithRole(Role::Admin);
        $producer = $this->producer();
        $this->product($producer);
        $offer = $this->contractOffer($producer);
        $purchase = $this->groupPurchase($producer, $this->distributions($producer, 1)->first(), ['contract_offer_id' => $offer->id]);

        foreach (["/gestion/offres-de-contrat/{$offer->id}/modifier", '/gestion/offres-de-contrat/ajouter', "/gestion/commandes-groupees/{$purchase->slug}/modifier",
            '/gestion/commandes-groupees', '/gestion/offres-de-contrat', "/gestion/producteurs/{$producer->slug}/modifier"] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    #[Test]
    public function seuls_les_adherents_a_jour_ecrivent_a_tous(): void
    {
        $sender = $this->member();
        $reader = $this->member();
        $optedOut = tap($this->member())->update(['accepts_member_messages' => false]);

        $this->actingAs($this->member(paid: false))
            ->post('/mon-espace/echanges', ['audience' => 'adherents', 'subject' => 'Covoiturage', 'body' => 'Qui monte au Grand-Bornand ?'])
            ->assertSessionHasErrors('audience');

        $this->actingAs($sender)
            ->post('/mon-espace/echanges', ['audience' => 'adherents', 'subject' => 'Covoiturage', 'body' => 'Qui monte au Grand-Bornand ?'])
            ->assertRedirect('/mon-espace/echanges');

        Mail::assertQueued(MemberMessageMail::class, fn ($m) => $m->hasTo($reader->email) && $m->hasReplyTo($sender->email));
        Mail::assertNotQueued(MemberMessageMail::class, fn ($m) => $m->hasTo($optedOut->email) || $m->hasTo($sender->email));
        $this->assertSame(MessageAudience::Members, MemberMessage::first()->audience);
    }

    #[Test]
    public function un_message_sur_un_producteur_va_a_son_referent(): void
    {
        $referent = $this->userWithRole(Role::Referent);
        $producer = $this->producer($referent);

        $this->actingAs($this->member())
            ->post('/mon-espace/echanges', ['audience' => 'referent', 'producer_id' => $producer->id, 'subject' => 'Tomme', 'body' => 'Reste-t-il des tommes ?'])
            ->assertRedirect();

        Mail::assertQueued(MemberMessageMail::class, 1);
        Mail::assertQueued(MemberMessageMail::class, fn ($m) => $m->hasTo($referent->email));
    }

    #[Test]
    public function le_producteur_telecharge_la_liste_seulement_si_le_referent_l_autorise(): void
    {
        $account = $this->userWithRole(Role::Producer);
        $producer = $this->producer(attributes: ['user_id' => $account->id]);
        $this->product($producer);
        $offer = $this->contractOffer($producer);

        $this->actingAs($account)->get("/espace-producteur/contrats/{$offer->id}/adherents.csv")->assertForbidden();

        $offer->update(['share_members_with_producer' => true]);
        $this->actingAs($account)->get("/espace-producteur/contrats/{$offer->id}/adherents.csv")->assertOk();
        $this->actingAs($account)->get("/espace-producteur/contrats/{$offer->id}/adherents.pdf")->assertOk();
    }
}
