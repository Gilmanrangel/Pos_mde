<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // Relasi ke supplier
            $table->foreignId('supplier_id')
                  ->constrained('suppliers')
                  ->onDelete('cascade');

            // Relasi ke user (admin/kasir yang input pembelian)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Tanggal pembelian
            $table->date('tanggal');

            // Total harga pembelian
            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
