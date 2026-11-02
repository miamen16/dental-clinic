<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status values are stored lowercase (see Appointment::STATUSES, Invoice::STATUSES).
     */
    public function up(): void
    {
        // Normalize status values to lowercase for appointments, invoices, treatment plans, and treatment items
        foreach (['appointments', 'invoices', 'treatment_plans', 'treatment_items'] as $table) {
            DB::table($table)->update(['status' => DB::raw('LOWER(status)')]);
        }

        // "unpaid" was the old column default but was never a valid invoice status
        DB::table('invoices')->where('status', 'unpaid')->update(['status' => 'pending']);

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status', 30)->default('unpaid')->change();
        });
    }
};
