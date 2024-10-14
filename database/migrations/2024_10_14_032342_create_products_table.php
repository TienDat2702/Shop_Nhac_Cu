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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id'); 
            $table->unsignedBigInteger('brand_id'); 
            $table->string('name', 125);
            $table->string('image', 125)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('price_sale', 10, 2);
            $table->integer('view')->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->text('summary')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
