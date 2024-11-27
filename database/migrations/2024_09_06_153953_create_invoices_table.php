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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // Foreign key to customers table
            $table->foreignId('user_id');
            $table->json('invoice_items'); // JSON field for invoice items
            $table->integer('total_qnt'); // Total quantity of products
            $table->decimal('sub_total', 10, 2); // Subtotal (before tax and discount)
            $table->decimal('tax', 10, 2); // Tax applied
            $table->decimal('discount', 10, 2)->default(0); // Discount applied
            $table->decimal('total', 10, 2); // Final total after tax and discount
            $table->string('payment_type'); // Payment method (cash, card, mobile banking, etc.)
            $table->timestamps();
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
