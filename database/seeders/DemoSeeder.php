<?php

namespace Database\Seeders;

use App\Enums\Audience;
use App\Enums\DistributionStatus;
use App\Enums\Frequency;
use App\Enums\InstallmentStatus;
use App\Enums\Participation;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Filament\Resources\GroupPurchases\Tables\GroupPurchasesTable;
use App\Models\ContractOffer;
use App\Models\Distribution;
use App\Models\GroupPurchase;
use App\Models\Installment;
use App\Models\Producer;
use App\Models\User;
use App\Services\ContractPlanner;
use App\Services\OrderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DemoSeeder extends Seeder
{
    private const PASSWORD = 'panier2027';

    /** @var list<int> produits de la vitrine, hors offres de contrat */
    private array $showcaseOnly = [];

    /** Adhérents fictifs. */
    private const MEMBERS = [
        ['Claire', 'Martin'], ['Paul', 'Bernard'], ['Léa', 'Rossi'], ['Hugo', 'Petit'], ['Emma', 'Durand'],
        ['Louis', 'Lefèvre'], ['Chloé', 'Moreau'], ['Gabriel', 'Simon'], ['Manon', 'Laurent'], ['Jules', 'Michel'],
        ['Inès', 'Garcia'], ['Arthur', 'David'], ['Camille', 'Bertrand'], ['Nathan', 'Roux'], ['Sarah', 'Vincent'],
        ['Tom', 'Fournier'], ['Lucie', 'Girard'], ['Noé', 'Bonnet'], ['Alice', 'Dupuis'], ['Victor', 'Lambert'],
    ];

    public function run(): void
    {
        $this->users();
        $this->producers();
        $this->distributions();
        $this->offers();
        $this->groupPurchases();
        $this->bakery();
        $this->ordersAndContracts();
        $this->breadOrders();
    }

    private function user(string $first, string $last, string $email, array $roles, bool $member = true): User
    {
        $user = User::updateOrCreate(['email' => $email], [
            'first_name' => $first, 'last_name' => $last, 'password' => self::PASSWORD,
            'phone' => '06 '.implode(' ', str_split((string) random_int(10000000, 99999999), 2)),
            'city' => 'Thônes', 'postal_code' => '74230', 'share_phone' => (bool) random_int(0, 1),
            'email_verified_at' => now(),
        ]);
        $user->syncRoles(array_map(fn (Role $r) => $r->value, $roles));
        if ($member) {
            foreach ([2026, 2027] as $year) {
                $user->memberships()->updateOrCreate(['year' => $year], [
                    'amount_cents' => 2000, 'payment_method' => PaymentMethod::Cheque, 'paid_at' => Carbon::create($year - 1, 12, 6),
                ]);
            }
        }

        return $user;
    }

    private function users(): void
    {
        $this->user('Anne', 'Durand', 'admin@exemple.fr', [Role::Admin, Role::Referent, Role::Member]);
        $this->user('Jean', 'Perrier', 'jean.perrier@exemple.fr', [Role::Referent, Role::Member]);
        $this->user('Marc', 'Blanc', 'marc.blanc@exemple.fr', [Role::Referent, Role::Member]);
        foreach (self::MEMBERS as $i => [$first, $last]) {
            $email = $i === 0 ? 'claire.martin@exemple.fr' : Str::slug("$first.$last", '.').'@exemple.fr';
            $this->user($first, $last, $email, [Role::Member]);
        }
        // Un adhérent qui vient de créer son compte, adhésion pas encore réglée.
        $this->user('Zoé', 'Nouvelle', 'zoe.nouvelle@exemple.fr', [Role::Member], member: false);
        // Compte de l'espace producteur (Le Corti des Aravis).
        $this->user('François', 'Sylvestre-Boncheval', 'corti@exemple.fr', [Role::Producer], member: false);
    }

    private function producers(): void
    {
        $disk = Storage::disk('public');
        $media = database_path('seeders/media');
        foreach (File::allFiles($media) as $file) {
            $disk->put($file->getRelativePathname(), $file->getContents());
        }
        $disk->makeDirectory('producteurs');

        $jean = User::firstWhere('email', 'jean.perrier@exemple.fr');
        $marc = User::firstWhere('email', 'marc.blanc@exemple.fr');
        $corti = User::firstWhere('email', 'corti@exemple.fr');

        foreach (json_decode(File::get(database_path('seeders/data/producers.json')), true) as $data) {
            $products = $data['products'] ?? [];
            unset($data['products']);
            $participation = Participation::from($data['participation']);
            $producer = Producer::updateOrCreate(['slug' => $data['slug']], [
                ...$data,
                'is_published' => true,
                'referent_id' => $participation === Participation::GroupPurchase ? $marc->id : $jean->id,
                'cheque_payee' => $data['cheque_payee'] ?? $data['name'],
                'user_id' => $data['slug'] === 'le-corti-des-aravis' ? $corti->id : null,
            ]);
            // « in_offer: false » : produit de la vitrine seulement (par exemple la viande au kilo du GAEC Bibollet).
            foreach ($products as $i => $product) {
                $inOffer = $product['in_offer'] ?? true;
                unset($product['in_offer']);
                $saved = $producer->products()->updateOrCreate(['name' => $product['name'], 'unit' => $product['unit']], [...$product, 'sort' => $i]);
                if (! $inOffer) {
                    $this->showcaseOnly[] = $saved->id;
                }
            }
        }
    }

    /** Une distribution un samedi sur deux, de novembre 2026 à décembre 2027. */
    private function distributions(): void
    {
        $dates = collect([Carbon::parse('2026-11-21'), Carbon::parse('2026-12-05'), Carbon::parse('2026-12-19')]);
        for ($d = Carbon::parse('2027-01-16'); $d->lte(Carbon::parse('2027-12-18')); $d->addWeeks(2)) {
            $dates->push($d->copy());
        }

        $contract = Producer::whereIn('participation', [Participation::Contract, Participation::Both])->get()->keyBy('slug');

        foreach ($dates->values() as $i => $date) {
            $distribution = Distribution::updateOrCreate(['date' => $date->toDateString()], [
                'place' => config('amap.distribution.place'),
                'starts_at' => '09:30', 'ends_at' => '10:30',
                'status' => $date->isPast() ? DistributionStatus::SummariesSent : DistributionStatus::Planned,
            ]);

            $present = $contract->filter(fn (Producer $p) => match ($p->slug) {
                // Légumes du Corti : saison de juin à fin novembre.
                'le-corti-des-aravis' => $date->between('2027-06-01', '2027-11-30'),
                // La Ferme du Ponant vient une distribution sur deux.
                'la-ferme-du-ponant' => $i % 2 === 0,
                // Chèvres taries en hiver.
                'la-cabane-marjolaine' => ! in_array($date->month, [1, 2], true),
                default => true,
            });
            $distribution->producers()->sync($present->pluck('id'));
        }
    }

    private function offers(): void
    {
        $plans = [['installments' => 1, 'months' => [3]], ['installments' => 3, 'months' => [3, 6, 9]]];

        foreach (Producer::whereIn('participation', [Participation::Contract, Participation::Both])->where('slug', '!=', 'boulangerie-chez-geoffrey')->get() as $producer) {
            $corti = $producer->slug === 'le-corti-des-aravis';
            // La Ferme du Coteau propose des contrats trimestriels.
            $quarterly = $producer->slug === 'la-ferme-du-coteau';
            $title = match (true) {
                $corti => 'Paniers légumes et fruits 2027',
                $quarterly => 'Fruits – 1er trimestre 2027',
                default => 'Contrat 2027',
            };
            $offer = ContractOffer::updateOrCreate(['producer_id' => $producer->id, 'title' => $title], [
                'starts_on' => $corti ? '2027-06-01' : '2027-01-01',
                'ends_on' => match (true) {
                    $corti => '2027-11-30',
                    $quarterly => '2027-03-31',
                    default => '2027-12-31',
                },
                // Places limitées chez La Chèvrerie de Lily (premier arrivé, premier servi).
                'max_contracts' => $producer->slug === 'la-chevrerie-de-lily' ? 9 : null,
                'share_members_with_producer' => $corti,
                'announce_to' => Audience::AllMembers,
                'announced_at' => '2026-11-14 08:05',
                'opens_at' => '2026-11-14 08:00',
                'closes_at' => '2026-12-20 20:00',
                'frequencies' => match ($producer->slug) {
                    'gaec-bibollet', 'la-ferme-du-coteau' => [Frequency::Every->value, Frequency::Alternate->value, Frequency::Custom->value],
                    default => [Frequency::Every->value, Frequency::Alternate->value],
                },
                'payment_plans' => match (true) {
                    $corti => [...$plans, ['installments' => 6, 'months' => [6, 7, 8, 9, 10, 11]]],
                    $quarterly => [['installments' => 1, 'months' => [1]], ['installments' => 3, 'months' => [1, 2, 3]]],
                    default => $plans,
                },
                'terms' => '<p><strong>Engagement du producteur :</strong> livrer des produits de saison, au fur et à mesure des récoltes, et informer les adhérents sur ses pratiques.</p><p><strong>Engagement de l\'adhérent :</strong> régler d\'avance, récupérer ou faire récupérer ses produits, accepter les aléas de la production. En cas d\'absence, aucun remboursement ni rattrapage.</p><p><em>Texte d\'exemple du prototype.</em></p>',
                'is_published' => true,
            ]);
            $offer->products()->sync($producer->products()->whereNotIn('id', $this->showcaseOnly)->pluck('id'));
        }
    }

    /**
     * Boulangerie : engagement annuel en montant, puis pain commandé tous les 15 jours
     * (ouverture le mercredi à 12 h, clôture le jeudi à 18 h). La boulangère déduit les
     * commandes du solde ; les adhérents non engagés commandent aussi et règlent sur place.
     */
    private function bakery(): void
    {
        $producer = Producer::firstWhere('slug', 'boulangerie-chez-geoffrey');
        // Montants proposés sur la boutique actuelle (producers.json).
        $amounts = $producer->products()->where('is_amount', true)->get();

        $offer = ContractOffer::updateOrCreate(['producer_id' => $producer->id, 'title' => 'Engagement pain 2027'], [
            'starts_on' => '2026-11-21', 'ends_on' => '2027-12-31',
            'opens_at' => '2026-11-10 08:00', 'closes_at' => '2026-12-20 20:00',
            'frequencies' => [],
            'payment_plans' => [['installments' => 1, 'months' => [12]], ['installments' => 2, 'months' => [12, 6]]],
            'share_members_with_producer' => true,
            'terms' => '<p>Vous vous engagez sur un montant pour l\'année. Tous les 15 jours, vous commandez le pain que vous voulez : la boulangère déduit chaque commande de votre engagement.</p><p><em>Texte d\'exemple du prototype.</em></p>',
            'is_published' => true,
        ]);
        $offer->products()->sync($amounts->pluck('id'));

        $first = Distribution::whereDate('date', '2026-11-21')->first();
        $model = GroupPurchase::updateOrCreate(['title' => 'Pain de la quinzaine – 21 novembre'], [
            'producer_id' => $producer->id, 'contract_offer_id' => $offer->id, 'referent_id' => $producer->referent_id,
            'description' => 'Pains au levain, cuits au feu de bois.',
            'opens_at' => '2026-11-18 12:00', 'closes_at' => '2026-11-19 18:00', 'distribution_id' => $first->id,
        ]);
        foreach ($producer->products()->where('is_amount', false)->get() as $i => $bread) {
            $model->items()->updateOrCreate(['name' => $bread->name], ['packaging' => $bread->unit === 'kg' ? '1 kg' : $bread->unit, 'price_cents' => $bread->price_cents, 'max_per_member' => 4, 'sort' => $i]);
        }
        GroupPurchasesTable::repeat($model, Carbon::parse('2027-02-28'));

        // Pour la démonstration, la première commande est déjà ouverte.
        $model->update(['opens_at' => now()->subHour()->startOfHour()]);
    }

    /** Une partie des adhérents engagés a déjà commandé son pain. */
    private function breadOrders(): void
    {
        $purchase = GroupPurchase::where('title', 'Pain de la quinzaine – 21 novembre')->with('items', 'contractOffer.contracts.user')->first();
        foreach ($purchase->contractOffer->contracts->take(5) as $contract) {
            app(OrderService::class)->saveGroupOrder($contract->user, $purchase, $purchase->items->random(2)->mapWithKeys(fn ($item) => [$item->id => mt_rand(1, 2)])->all());
        }
        $purchase->update(['announced_at' => now()]);
    }

    private function groupPurchases(): void
    {
        $marc = User::firstWhere('email', 'marc.blanc@exemple.fr');
        $dist = fn (string $date) => Distribution::whereDate('date', $date)->value('id');
        $producer = fn (string $slug) => Producer::firstWhere('slug', $slug)->id;

        $purchases = [
            ['Huile d\'olive Vale Douro', 'vale-douro', '2026-11-10 08:00', '2026-11-29 20:00', '2026-12-05',
                'La grande cuvée de Dominique Viard, livrée juste avant les fêtes.', 'Chèque à l\'ordre de SAS Vale Douro, à remettre à la distribution.',
                [['Huile d\'olive vierge extra', '50 cl', 1650, 6], ['Huile d\'olive vierge extra', '3 l (bag-in-box)', 7200, 2]]],
            ['Vins du Mas d\'Intras – fin d\'année', 'mas-d-intras', '2026-11-12 08:00', '2026-12-06 20:00', '2026-12-19',
                'Vins bio et biodynamiques d\'Ardèche.', 'Chèque à l\'ordre du Mas d\'Intras.',
                [['Cuvée Isidora 2023 (Demeter)', '75 cl', 1150, 12], ['Grenache 2022', '75 cl', 980, 12], ['Carignan 2023', '75 cl', 1050, 12]]],
            ['Farines du Fournil des Paysans', 'le-fournil-des-paysans', '2027-01-04 08:00', '2027-01-24 20:00', '2027-01-30',
                'Farines bio moulues sur meule de pierre.', 'Chèque à l\'ordre du GAEC Le Fournil des Paysans.',
                [['Farine de blé T80', '5 kg', 1450, null], ['Farine de blés paysans T80', '5 kg', 1800, null], ['Farine de petit épeautre', '1 kg', 650, null]]],
            ['Glaces et sorbets de Lait\'able', 'lait-able-des-frangins', '2026-11-01 08:00', '2026-11-12 20:00', '2026-11-21',
                'Glaces au lait de la ferme et sorbets de saison.', 'Chèque à l\'ordre de Lait\'able des Frangins.',
                [['Glace vanille', '500 ml', 750, null], ['Sorbet cassis', '500 ml', 720, null]]],
        ];

        foreach ($purchases as [$title, $slug, $opens, $closes, $date, $description, $payment, $items]) {
            $purchase = GroupPurchase::updateOrCreate(['title' => $title], [
                'producer_id' => $producer($slug), 'referent_id' => $marc->id, 'description' => $description, 'announced_at' => $opens,
                'opens_at' => $opens, 'closes_at' => $closes, 'distribution_id' => $dist($date), 'payment_info' => $payment,
            ]);
            foreach ($items as $i => [$name, $packaging, $price, $max]) {
                $purchase->items()->updateOrCreate(['name' => $name, 'packaging' => $packaging], ['price_cents' => $price, 'max_per_member' => $max, 'sort' => $i]);
            }
        }
    }

    /** Quelques contrats et commandes, pour que récapitulatifs, émargements et pointage aient du contenu. */
    private function ordersAndContracts(): void
    {
        $orders = app(OrderService::class);
        $planner = app(ContractPlanner::class);
        mt_srand(2027);

        $members = User::role(Role::Member->value)->whereHas('memberships', fn ($q) => $q->whereNotNull('paid_at'))
            ->where('email', '!=', 'claire.martin@exemple.fr')->get();

        $offers = ContractOffer::with('producer', 'products')->get();
        foreach ($members as $member) {
            foreach ($offers->random(mt_rand(1, 4)) as $offer) {
                $distributions = $offer->distributions();
                $lines = [];
                foreach ($offer->products->random(1) as $product) {
                    $frequency = mt_rand(0, 2) ? Frequency::Every : Frequency::Alternate;
                    $lines[] = [
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'frequency' => $frequency,
                        // Engagement en montant sans fréquence : les livraisons viennent des commandes.
                        'distribution_ids' => $product->is_amount && ! $offer->allowedFrequencies()
                            ? [] : $planner->distributionIds($distributions, $frequency, mt_rand(0, 1)),
                    ];
                }
                $plan = collect($offer->paymentPlans())->random();
                try {
                    $orders->saveContract($member, $offer, $lines, $plan['installments'], mt_rand(0, 3) ? PaymentMethod::Cheque : PaymentMethod::Transfer);
                } catch (ValidationException) {
                    // Contrat complet : l'adhérent n'a pas eu de place.
                }
            }
        }

        // Claire a déjà ses œufs : ses autres souscriptions montrent les œufs en petit dans le calendrier.
        $claire = User::firstWhere('email', 'claire.martin@exemple.fr');
        $eggs = $offers->first(fn ($o) => $o->producer->slug === 'les-oeufs-d-alex');
        $orders->saveContract($claire, $eggs, [[
            'product_id' => $eggs->products->first()->id, 'quantity' => 1, 'frequency' => Frequency::Alternate,
            'distribution_ids' => $planner->distributionIds($eggs->distributions(), Frequency::Alternate),
        ]], 1, PaymentMethod::Cheque);

        // Commandes groupées ouvertes : une partie des adhérents a déjà commandé.
        foreach (GroupPurchase::open()->with('items')->get() as $purchase) {
            foreach ($members->random(8) as $member) {
                $orders->saveGroupOrder($member, $purchase, $purchase->items->random(mt_rand(1, 2))->mapWithKeys(fn ($item) => [$item->id => min(mt_rand(1, 3), $item->max_per_member ?? 3)])->all());
            }
        }

        // Commande groupée déjà clôturée (livrée le 21 novembre) : créée en l'ouvrant temporairement.
        $closed = GroupPurchase::where('closes_at', '<', now())->with('items')->first();
        if ($closed) {
            $closes = $closed->closes_at;
            $closed->update(['closes_at' => now()->addHour()]);
            foreach ($members->random(10) as $member) {
                $orders->saveGroupOrder($member, $closed->fresh(), $closed->items->random(mt_rand(1, 2))->mapWithKeys(fn ($item) => [$item->id => mt_rand(1, 2)])->all());
            }
            $closed->update(['closes_at' => $closes]);
        }

        // Une partie des premiers règlements est déjà reçue ou encaissée.
        $admin = User::firstWhere('email', 'admin@exemple.fr');
        foreach (Installment::where('number', 1)->get() as $installment) {
            $roll = mt_rand(0, 9);
            if ($roll < 4) {
                $installment->mark(InstallmentStatus::Received, $admin);
            } elseif ($roll < 6) {
                $installment->mark(InstallmentStatus::Cashed, $admin);
            }
        }
    }
}
