<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('owner_id');
            $table->char('title');
            $table->text('description');
            $table->char('thumbnail_url')->nullable();
            $table->char('demo_url')->nullable();
            $table->char('source_url')->nullable();
            $table->unsignedInteger('price');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
