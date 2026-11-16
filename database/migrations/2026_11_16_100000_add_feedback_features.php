<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Retours de novembre 2026 : icônes coloriées, engagements en montant, places limitées,
 * commandes liées à un engagement, annonces automatiques et échanges entre adhérents.
 */
return new class extends Migration
{
    /** Anciennes icônes (emoji) => icône de la banque. */
    private const EMOJIS = [
        '🥕' => 'carotte', '🍎' => 'pomme', '🧀' => 'fromage', '🥛' => 'lait', '🥚' => 'oeuf', '🥩' => 'viande',
        '🍞' => 'pain', '🌾' => 'ble', '🍷' => 'vin', '🧃' => 'jus', '🍯' => 'miel', '🫒' => 'olive', '🛍️' => 'sac',
    ];

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('icon', 40)->nullable()->change();
            $table->string('icon_color', 20)->nullable()->after('icon');
            // Libellé de la légende du calendrier, par exemple « Fromage de chèvre ».
            $table->string('category_label')->nullable()->after('icon_color');
            // Montant d'engagement : le prix est le total de la saison, quel que soit le nombre de livraisons.
            $table->boolean('is_amount')->default(false)->after('price_cents');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('icon', 40)->nullable()->change();
            $table->string('icon_color', 20)->nullable()->after('icon');
        });

        foreach (['products', 'deliveries'] as $table) {
            foreach (self::EMOJIS as $emoji => $key) {
                DB::table($table)->where('icon', $emoji)->update(['icon' => $key, 'icon_color' => 'vert']);
            }
        }

        Schema::table('contract_offers', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_contracts')->nullable()->after('payment_plans');
            $table->boolean('share_members_with_producer')->default(false)->after('max_contracts');
            $table->string('announce_to')->nullable()->after('is_published'); // Audience
            $table->timestamp('announced_at')->nullable()->after('announce_to');
        });

        Schema::table('contract_lines', function (Blueprint $table) {
            $table->boolean('is_amount')->default(false)->after('unit_price_cents');
        });

        Schema::table('group_purchases', function (Blueprint $table) {
            // Commande liée à un engagement (ex. boulangerie) : les adhérents engagés ne règlent pas,
            // le producteur déduit la commande de leur solde.
            $table->foreignId('contract_offer_id')->nullable()->after('producer_id')->constrained()->nullOnDelete();
        });

        Schema::table('group_orders', function (Blueprint $table) {
            $table->foreignId('contract_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('accepts_member_messages')->default(true)->after('share_phone');
        });

        Schema::create('member_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->string('audience'); // MessageAudience
            $table->foreignId('producer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->text('body');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_messages');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('accepts_member_messages'));
        Schema::table('group_orders', fn (Blueprint $table) => $table->dropConstrainedForeignId('contract_id'));
        Schema::table('group_purchases', fn (Blueprint $table) => $table->dropConstrainedForeignId('contract_offer_id'));
        Schema::table('contract_lines', fn (Blueprint $table) => $table->dropColumn('is_amount'));
        Schema::table('contract_offers', fn (Blueprint $table) => $table->dropColumn(['max_contracts', 'share_members_with_producer', 'announce_to', 'announced_at']));
        Schema::table('deliveries', fn (Blueprint $table) => $table->dropColumn('icon_color'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['icon_color', 'category_label', 'is_amount']));
    }
};
