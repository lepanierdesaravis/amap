<?php

namespace Tests\Feature;

use App\Enums\Frequency;
use App\Services\ContractPlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Support\BuildsAmap;
use Tests\TestCase;

class ContractPlannerTest extends TestCase
{
    use BuildsAmap, RefreshDatabase;

    #[Test]
    public function la_frequence_choisit_les_dates_de_livraison(): void
    {
        $planner = new ContractPlanner;
        $distributions = $this->distributions($this->producer(), 6);
        $ids = $distributions->pluck('id')->all();

        $this->assertSame($ids, $planner->distributionIds($distributions, Frequency::Every));
        $this->assertSame([$ids[0], $ids[2], $ids[4]], $planner->distributionIds($distributions, Frequency::Alternate));
        $this->assertSame([$ids[1], $ids[3], $ids[5]], $planner->distributionIds($distributions, Frequency::Alternate, 1));
        $this->assertSame([$ids[1], $ids[4]], $planner->distributionIds($distributions, Frequency::Custom, customIds: [$ids[4], $ids[1], 999]));
    }

    #[Test]
    public function l_echeancier_suit_les_mois_de_l_offre(): void
    {
        $offer = $this->contractOffer($this->producer(), ['starts_on' => '2027-01-01', 'ends_on' => '2027-12-31']);

        $installments = (new ContractPlanner)->installments($offer, 21202, 3);

        $this->assertSame([7067, 7067, 7068], array_column($installments, 'amount_cents'));
        $this->assertSame(['2027-03-01', '2027-06-01', '2027-09-01'], array_map(fn ($i) => $i['due_on']->toDateString(), $installments));
    }

    #[Test]
    public function un_echeancier_inconnu_revient_a_un_seul_reglement(): void
    {
        $offer = $this->contractOffer($this->producer(), ['starts_on' => '2027-01-01', 'ends_on' => '2027-12-31']);

        $installments = (new ContractPlanner)->installments($offer, 10000, 7);

        $this->assertCount(1, $installments);
        $this->assertSame(10000, $installments[0]['amount_cents']);
        $this->assertSame([], (new ContractPlanner)->installments($offer, 0, 3));
    }
}
