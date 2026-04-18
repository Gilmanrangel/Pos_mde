<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_details', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->integer('qty');
    $table->bigInteger('harga_jual'); // harga snapshot
    $table->bigInteger('subtotal');   // qty * harga
    $table->timestamps();

    $table->index('product_id');
});

    }

    public function down(): void {
        Schema::dropIfExists('sale_details');
    }
};
