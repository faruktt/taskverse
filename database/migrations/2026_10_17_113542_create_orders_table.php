<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('gig_id')->constrained('gigs')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->enum('package', ['basic','standard','premium'])->default('basic');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_days');
            $table->enum('status', ['pending','in_progress','delivered','revision','completed','cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid','paid','refunded'])->default('unpaid');
            $table->text('requirements')->nullable();
            $table->text('delivery_note')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
