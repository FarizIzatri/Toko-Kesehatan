<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            
            $table->string('product_name'); // Snapshot nama produk
            $table->integer('quantity');
            $table->unsignedBigInteger('price'); // Harga satuan
            $table->unsignedBigInteger('total_amount'); // quantity * price
            
            $table->enum('status', ['completed', 'refunded'])->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_transactions');
    }
};