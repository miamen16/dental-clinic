<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable();

            $table->string('specialty', 100)->nullable();

            $table->string('license_number', 100)
                ->nullable()
                ->unique();

            $table->text('bio')->nullable();

            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['license_number']);

            $table->dropColumn([
                'phone',
                'specialty',
                'license_number',
                'bio',
                'is_active',
            ]);
        });
    }
};
