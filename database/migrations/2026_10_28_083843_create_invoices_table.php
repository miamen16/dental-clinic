<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // we used ->restrictOnDelete(); to disable the deletion of a patient if there are associated invoices. This ensures that we maintain data integrity and prevent accidental loss of important financial records.
    // we used ->nullable(); for the appointment_id and created_by foreign keys. This allows for the possibility that an invoice may not be directly linked to a specific appointment or user, providing flexibility in how invoices are generated and managed.
    // we used ->constrained(); for the patient_id, appointment_id, and created_by foreign keys. This automatically sets up the foreign key constraints based on the referenced tables (patients, appointments, and users), ensuring referential integrity in the database.
    // we used ->nullOnDelete(); for the appointment_id and created_by foreign keys. This means that if an associated appointment or user is deleted, the corresponding foreign key in the invoices table will be set to NULL. This allows us to retain the invoice record even if the related appointment or user is removed, preserving historical data.
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->restrictOnDelete();

            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            $table->date('issued_at');
            $table->date('due_at')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->string('status', 30)->default('unpaid');
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            // Indexes
            $table->index(['patient_id', 'issued_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
