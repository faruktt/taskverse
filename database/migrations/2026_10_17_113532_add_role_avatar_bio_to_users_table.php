<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['buyer', 'seller'])->default('buyer')->after('email');
            $table->string('avatar')->nullable()->after('role');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('country')->nullable()->after('bio');
            $table->boolean('is_banned')->default(false)->after('country');
            $table->boolean('is_seller_verified')->default(false)->after('is_banned');
            $table->string('phone')->nullable()->after('is_seller_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','avatar','bio','country','is_banned','is_seller_verified','phone']);
        });
    }
};
