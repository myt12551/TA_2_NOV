<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the purchase_requests table.
 *
 * A purchase request (PR) captures a team member's formal request
 * for goods or services. PRs are later reviewed and, if approved,
 * converted into purchase orders (POs). Each PR contains high‑level
 * information about the request as well as the requesting user.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            // Unique PR number (e.g. PR-0001/MM/YYYY)
            $table->string('pr_number')->unique();
            // User who submitted the request
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            // Date the request was submitted
            $table->date('request_date');
            // Current status: draft, pending, approved, rejected, cancelled
            $table->string('status')->default('pending');
            // Optional description or notes
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};