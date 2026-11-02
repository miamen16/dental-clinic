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
        Schema::create('invoice_item_taxes', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('invoice_item_id')
                ->constrained('invoice_items')
                ->cascadeOnDelete();

            $table->foreignId('tax_id')
                ->nullable()
                ->constrained('taxes')
                ->nullOnDelete();

            // Tax Snapshot
            $table->string('tax_name');
            $table->string('tax_type', 20);
            $table->decimal('tax_rate', 12, 4);

            // Applied Tax Amount
            $table->decimal('tax_amount', 12, 2);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_item_taxes');
    }
};
