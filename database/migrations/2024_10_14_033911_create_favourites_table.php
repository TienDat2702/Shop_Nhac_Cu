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
        Schema::create('favourites', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('customer_id'); 
            $table->unsignedBigInteger('product_id'); 
            $table->string('name', 125); 
            $table->string('image', 255)->nullable();
            $table->decimal('price', 10, 2); // Giá gốc sản phẩm
            $table->decimal('price_sale', 10, 2)->nullable(); // Giá bán sau khi giảm giá
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
