<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 255)->nullable();
            $table->longText('description')->nullable();

            $table->decimal('goal_amount', 12);
            $table->string('currency', 3)->default('EUR');

            $table->string('status', 30)->default('draft');

            $table->foreignUlid('campaign_category_id')
                ->nullable()
                ->constrained('campaign_categories')
                ->nullOnDelete();

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            $table->foreignUlid('created_by_user_id')->nullable();
            $table->foreignUlid('approved_by_user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
