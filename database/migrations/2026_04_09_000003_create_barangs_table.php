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
        // Membuat tabel utama untuk modul CRUD barang.
        Schema::create('barangs', function (Blueprint $table) {
            // Primary key auto increment.
            $table->id();

            // Nama barang untuk ditampilkan di list dan detail.
            $table->string('nama');

            // SKU (kode unik barang) untuk identifikasi inventori.
            $table->string('sku')->unique();

            // Jumlah stok saat ini, tidak boleh negatif.
            $table->unsignedInteger('stok')->default(0);

            // Harga disimpan sebagai desimal (14 digit, 2 angka desimal).
            $table->decimal('harga', 14, 2)->default(0);

            // Catatan tambahan barang (opsional).
            $table->text('deskripsi')->nullable();

            // created_at dan updated_at otomatis dari Laravel.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: hapus tabel jika migration dibatalkan.
        Schema::dropIfExists('barangs');
    }
};
