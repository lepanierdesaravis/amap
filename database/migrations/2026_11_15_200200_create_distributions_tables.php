<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->time('starts_at')->default('09:30');
            $table->time('ends_at')->default('10:30');
            $table->string('place');
            $table->string('status'); // DistributionStatus
            $table->text('notes')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('summaries_sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('distribution_producer', function (Blueprint $table) {
            $table->foreignId('distribution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->primary(['distribution_id', 'producer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_producer');
        Schema::dropIfExists('distributions');
    }
};
