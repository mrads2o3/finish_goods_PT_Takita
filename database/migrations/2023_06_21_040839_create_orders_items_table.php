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
        Schema::create('orders_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('orders_id')->unsigned()->index();
            $table->foreign('orders_id')->references('id')->on('orders')->onUpdate('cascade');
            $table->bigInteger('items_id')->unsigned()->index();
            $table->foreign('items_id')->references('id')->on('items')->onUpdate('cascade');
            $table->integer('price');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_items');
    }
};
