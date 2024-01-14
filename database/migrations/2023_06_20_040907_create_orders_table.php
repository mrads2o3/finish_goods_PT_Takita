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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->bigInteger('cust_id')->unsigned()->index();
            $table->foreign('cust_id')->references('id')->on('customers')->onUpdate('cascade');
            $table->date('request_date');
            $table->date('send_date');
            $table->timestamp('sending_at')->nullable();
            $table->timestamp('complete_at')->nullable();
            $table->timestamp('cancel_at')->nullable();
            $table->set('status', ['pending', 'sending', 'complete', 'cancel']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
