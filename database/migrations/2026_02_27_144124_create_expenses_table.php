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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('activity')->comment('Kegiatan transaksi');
            $table->enum('type', ['operasional', 'asset', 'stok_barang'])->comment('Tipe transaksi');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->comment('Kategori produk');
            $table->enum('status', ['selesai', 'belum_tuntas'])->default('selesai')->comment('Status transaksi');
            $table->decimal('amount', 15, 2)->comment('Biaya');
            $table->text('description')->nullable()->comment('Keterangan');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Created by user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
