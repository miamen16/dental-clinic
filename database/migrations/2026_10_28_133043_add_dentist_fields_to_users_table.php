<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // phone, specialty, license_number, bio and is_active are added by 2026_10_28_123825
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('experience')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('education')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'experience',
                'profile_picture',
                'education',
            ]);
        });
    }
};
