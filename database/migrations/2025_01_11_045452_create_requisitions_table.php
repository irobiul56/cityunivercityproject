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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of requisition
            $table->enum('is_regular', ['yes', 'no'])->default('yes'); // Status of requisition
            $table->unsignedBigInteger('requested_by'); // Foreign Key (e.g., user ID)
            $table->timestamps(); // Created at & Updated at

            // Foreign key to users table
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('received_by')->references('id')->on('depertments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
