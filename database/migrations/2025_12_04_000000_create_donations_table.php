<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('campaign_id')->index();
            $table->ulid('user_id')->index();
            $table->integer('amount_cents');
            $table->string('currency', 3)->default('EUR');
            $table->string('status', 32)->index(); // paid, failed
            $table->string('provider', 64);
            $table->string('provider_reference', 191)->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            // We keep FK constraints optional to allow flexible adapters; can be added later
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
