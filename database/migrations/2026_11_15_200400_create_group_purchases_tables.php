<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->foreignId('distribution_id')->constrained()->restrictOnDelete();
            $table->text('payment_info')->nullable();
            $table->boolean('announce_by_email')->default(true);
            $table->timestamp('announced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('group_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_purchase_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('packaging')->nullable();
            $table->unsignedInteger('price_cents');
            $table->unsignedSmallInteger('max_per_member')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('group_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_cents');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            $table->unique(['group_purchase_id', 'user_id']);
        });

        Schema::create('group_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_purchase_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('unit_price_cents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_order_lines');
        Schema::dropIfExists('group_orders');
        Schema::dropIfExists('group_purchase_items');
        Schema::dropIfExists('group_purchases');
    }
};
