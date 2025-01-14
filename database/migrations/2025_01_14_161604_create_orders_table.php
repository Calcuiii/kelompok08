<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();  // Kolom id (Primary key)
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Kolom product_id, mengacu pada kolom id di tabel products
            $table->integer('quantity'); // Kolom quantity
            $table->decimal('total_price', 10, 2); // Kolom total_price, dengan format angka desimal
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
