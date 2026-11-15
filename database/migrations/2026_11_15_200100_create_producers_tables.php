<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('farmer_names')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('photos')->nullable();
            $table->json('documents')->nullable();
            $table->json('product_types')->nullable();
            $table->json('labels')->nullable();
            $table->string('participation'); // Participation
            $table->string('cheque_payee')->nullable();
            $table->string('iban')->nullable();
            $table->string('siret')->nullable();
            // Le référent qui a créé le producteur est le seul à pouvoir le gérer (décision du 15/11/2026).
            $table->foreignId('referent_id')->nullable()->constrained('users')->nullOnDelete();
            // Compte de l'espace producteur (consultation : récapitulatifs, pointage).
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit'); // « panier », « pièce », « kg »…
            $table->unsignedInteger('price_cents');
            $table->string('icon', 16)->nullable(); // Emoji affiché dans le calendrier adhérent.
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('producers');
    }
};
