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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->date('transaction_date');

            $table->unsignedBigInteger('from_account_id');
            $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedBigInteger('to_account_id');
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->decimal('amount', 8, 2);
            $table->string('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
