<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->dateTime('sale_date');
            $table->decimal('total', 15, 2)->default(0);

            $table->enum('payment_method', [
                'tunai', 'transfer', 'qris'
            ])->default('tunai');

            $table->enum('payment_status', [
                'lunas', 'belum_bayar'
            ])->default('lunas');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['sale_date', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};