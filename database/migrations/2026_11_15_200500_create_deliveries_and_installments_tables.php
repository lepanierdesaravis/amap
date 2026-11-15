<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Qui reçoit quoi, combien, à quelle distribution.
        // Source unique du calendrier adhérent, des récapitulatifs producteurs et des feuilles d'émargement.
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distribution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->morphs('source'); // ContractLine ou GroupOrderLine
            $table->string('label'); // Nom du produit au moment de la commande
            $table->string('icon', 16)->nullable();
            $table->unsignedSmallInteger('quantity');
            $table->timestamps();
            $table->index(['distribution_id', 'producer_id']);
        });

        // Échéances de paiement, pointées par le producteur ou le référent.
        // L'AMAP n'encaisse rien : les paiements sont à l'ordre du producteur.
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->morphs('payable'); // Contract ou GroupOrder
            $table->unsignedTinyInteger('number');
            $table->unsignedInteger('amount_cents');
            $table->date('due_on');
            $table->string('payment_method'); // PaymentMethod
            $table->string('status'); // InstallmentStatus
            $table->string('cheque_number')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('marked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('intro')->nullable();
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('sender_type');
            $table->string('subject');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('installments');
        Schema::dropIfExists('deliveries');
    }
};
