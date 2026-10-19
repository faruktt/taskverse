<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->decimal('price_basic', 10, 2)->default(0);
            $table->decimal('price_standard', 10, 2)->nullable();
            $table->decimal('price_premium', 10, 2)->nullable();
            $table->string('basic_desc')->nullable();
            $table->string('standard_desc')->nullable();
            $table->string('premium_desc')->nullable();
            $table->integer('delivery_days')->default(3);
            $table->integer('revisions')->default(1);
            $table->string('tags')->nullable();
            $table->enum('status', ['pending','active','rejected','paused'])->default('pending');
            $table->unsignedInteger('views')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
