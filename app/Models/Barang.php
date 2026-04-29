<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Barang merepresentasikan tabel `barangs` di database.
 *
 * Laravel otomatis memetakan class `Barang` -> tabel `barangs`
 * karena konvensi plural snake_case.
 */
class Barang extends Model
{
    // Trait untuk mengaktifkan factory saat testing/seeding.
    use HasFactory;

    // Daftar kolom yang boleh diisi mass-assignment (create/update dari array).
    // Ini mencegah user mengirim field tak terduga lewat request.
    protected $fillable = [
        'nama',
        'sku',
        'stok',
        'harga',
        'deskripsi',
        'gambar',
    ];
}
