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
        $table->unsignedBigInteger('user_id');
        $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled']);
        $table->decimal('total_amount', 15, 2);
        $table->timestamps();

        // Menambahkan foreign key jika ada hubungan dengan tabel users
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
