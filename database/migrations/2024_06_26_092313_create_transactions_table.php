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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('transaction_type')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('tag_transaction', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained();
            $table->foreignId('transaction_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_transaction');
        Schema::dropIfExists('transactions');
    }
};
