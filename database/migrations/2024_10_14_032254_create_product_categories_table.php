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
        Schema::create('product_categories', function (Blueprint $table) {
<<<<<<< Updated upstream
            $table->id('id'); 
            $table->string('image', 225)->nullable(); // URL ảnh danh mục
            $table->text('summary')->nullable(); // tóm tắt danh mục sản phẩm
            $table->tinyInteger('publish')->default(1); 
            $table->integer('parent_id')->nullable(); 
            $table->text('description')->nullable(); 
            $table->timestamp('deleted_at')->nullable(); 
=======
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('publish')->default(2);
            $table->integer('parent_id'); // Chỉ cần một dòng này
            $table->integer('level');
            // $table->integer('parent_id'); // Dòng này cần phải xóa
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
>>>>>>> Stashed changes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
