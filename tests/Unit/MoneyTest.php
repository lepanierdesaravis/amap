<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    #[Test]
    public function les_centimes_restants_vont_sur_la_derniere_echeance(): void
    {
        $this->assertSame([26251, 26251, 26252], Money::split(78754, 3));
        $this->assertSame([5000], Money::split(5000, 1));
        $this->assertSame(78754, array_sum(Money::split(78754, 3)));
    }

    #[Test]
    public function un_montant_s_affiche_a_la_francaise(): void
    {
        $this->assertSame("1\u{202F}234,50 €", Money::format(123450));
        $this->assertSame('0,99 €', Money::format(99));
    }
}
