<?php

namespace Tests\Feature;

use App\Enums\Frequency;
use App\Enums\InstallmentStatus;
use App\Enums\PaymentMethod;
use App\Mail\ContractConfirmed;
use App\Mail\GroupOrderConfirmed;
use App\Models\Delivery;
use App\Models\GroupOrder;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\BuildsAmap;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use BuildsAmap, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        Mail::fake();
    }

    private function service(): OrderService
    {
        return app(OrderService::class);
    }

    #[Test]
    public function un_contrat_cree_les_livraisons_et_les_echeances(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer, 1500);
        $distributions = $this->distributions($producer, 4);
        $offer = $this->contractOffer($producer);
        $user = $this->member();

        $contract = $this->service()->saveContract($user, $offer, [[
            'product_id' => $product->id,
            'quantity' => 2,
            'frequency' => Frequency::Alternate,
            'distribution_ids' => [$distributions[0]->id, $distributions[2]->id],
        ]], 3, PaymentMethod::Cheque);

        $this->assertSame(6000, $contract->total_cents);
        $this->assertSame(2, Delivery::where('user_id', $user->id)->count());
        $this->assertSame([2000, 2000, 2000], $contract->installmentsDue()->orderBy('number')->pluck('amount_cents')->all());
        Mail::assertSent(ContractConfirmed::class);
    }

    #[Test]
    public function modifier_un_contrat_remplace_les_livraisons(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer);
        $distributions = $this->distributions($producer, 4);
        $offer = $this->contractOffer($producer);
        $user = $this->member();
        $line = fn (array $ids) => [['product_id' => $product->id, 'quantity' => 1, 'frequency' => Frequency::Custom, 'distribution_ids' => $ids]];

        $this->service()->saveContract($user, $offer, $line($distributions->pluck('id')->all()), 1, PaymentMethod::Cheque);
        $this->service()->saveContract($user, $offer, $line([$distributions[1]->id]), 1, PaymentMethod::Cheque);

        $this->assertSame([$distributions[1]->id], Delivery::where('user_id', $user->id)->pluck('distribution_id')->all());
        $this->assertSame(1, $user->contracts()->count());
    }

    #[Test]
    public function on_ne_modifie_plus_un_contrat_dont_un_paiement_est_pointe(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer);
        $distributions = $this->distributions($producer, 2);
        $offer = $this->contractOffer($producer);
        $user = $this->member();
        $lines = [['product_id' => $product->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => $distributions->pluck('id')->all()]];

        $contract = $this->service()->saveContract($user, $offer, $lines, 1, PaymentMethod::Cheque);
        $contract->installmentsDue()->first()->mark(InstallmentStatus::Received, $user);

        $this->expectException(ValidationException::class);
        $this->service()->saveContract($user, $offer, $lines, 1, PaymentMethod::Cheque);
    }

    #[Test]
    public function il_faut_une_adhesion_reglee_et_une_offre_ouverte(): void
    {
        $producer = $this->producer();
        $product = $this->product($producer);
        $distributions = $this->distributions($producer, 2);
        $lines = [['product_id' => $product->id, 'quantity' => 1, 'frequency' => Frequency::Every, 'distribution_ids' => $distributions->pluck('id')->all()]];

        $open = $this->contractOffer($producer);
        $closed = $this->contractOffer($producer, ['closes_at' => now()->subHour()]);

        foreach ([[$this->member(paid: false), $open], [$this->member(), $closed]] as [$user, $offer]) {
            try {
                $this->service()->saveContract($user, $offer, $lines, 1, PaymentMethod::Cheque);
                $this->fail('La souscription aurait dû être refusée.');
            } catch (ValidationException) {
                $this->assertSame(0, $user->contracts()->count());
            }
        }
    }

    #[Test]
    public function une_commande_groupee_respecte_le_maximum_par_adherent(): void
    {
        $producer = $this->producer();
        $purchase = $this->groupPurchase($producer, $this->distributions($producer, 1)->first());
        $item = $purchase->items()->create(['name' => 'Huile', 'packaging' => '3 l', 'price_cents' => 7200, 'max_per_member' => 2]);
        $user = $this->member();

        try {
            $this->service()->saveGroupOrder($user, $purchase, [$item->id => 3]);
            $this->fail('La quantité aurait dû être refusée.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('2 au maximum', $e->getMessage());
        }

        $order = $this->service()->saveGroupOrder($user, $purchase, [$item->id => 2]);
        $this->assertSame(14400, $order->total_cents);
        $this->assertSame(14400, (int) $order->installmentsDue()->sum('amount_cents'));
        Mail::assertSent(GroupOrderConfirmed::class);
    }

    #[Test]
    public function vider_une_commande_groupee_la_supprime(): void
    {
        $producer = $this->producer();
        $purchase = $this->groupPurchase($producer, $this->distributions($producer, 1)->first());
        $item = $purchase->items()->create(['name' => 'Huile', 'price_cents' => 1650]);
        $user = $this->member();

        $this->service()->saveGroupOrder($user, $purchase, [$item->id => 1]);
        $this->assertNull($this->service()->saveGroupOrder($user, $purchase, [$item->id => 0]));

        $this->assertSame(0, GroupOrder::count());
        $this->assertSame(0, Delivery::count());
    }
}
