<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ce que propose un producteur pour une saison.
        Schema::create('contract_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->json('frequencies'); // Frequency[] autorisées
            $table->json('payment_plans'); // [{installments: 3, months: [3, 6, 9]}, …]
            $table->longText('terms')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('contract_offer_product', function (Blueprint $table) {
            $table->foreignId('contract_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['contract_offer_id', 'product_id']);
        });

        // L'engagement d'un adhérent (« commande standard »).
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contract_offer_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('installments');
            $table->string('payment_method'); // PaymentMethod
            $table->unsignedInteger('total_cents');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'contract_offer_id']);
        });

        Schema::create('contract_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->string('frequency'); // Frequency
            $table->json('distribution_ids'); // Dates retenues (calculées depuis la fréquence ou choisies)
            $table->unsignedInteger('unit_price_cents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_lines');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_offer_product');
        Schema::dropIfExists('contract_offers');
    }
};
