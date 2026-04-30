<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller resource untuk operasi CRUD modul Barang.
 *
 * Method di class ini mengikuti standar Route::resource:
 * - index   : daftar data
 * - create  : form tambah data
 * - store   : simpan data baru
 * - show    : detail data
 * - edit    : form ubah data
 * - update  : simpan perubahan data
 * - destroy : hapus data
 */
class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang dengan pagination.
     */
    public function index(): View
    {
        // Ambil data terbaru dulu agar barang yang baru diinput muncul di atas.
        $barangs = Barang::query()->latest()->paginate(10);

        // Kirim variabel $barangs ke view index.
        return view('barang.index', compact('barangs'));
    }

    /**
     * Menampilkan halaman form untuk tambah barang baru.
     */
    public function create(): View
    {
        return view('barang.create');
    }

    /**
     * Menyimpan data barang baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi request agar data konsisten sebelum masuk database.
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:barangs,sku'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        // Simpan record baru menggunakan data yang sudah tervalidasi.
        Barang::create($validated);

        // Kembali ke halaman index dengan notifikasi sukses.
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail 1 data barang.
     *
     * Route model binding otomatis mengisi parameter $barang berdasarkan ID di URL.
     */
    public function show(Barang $barang): View
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form edit data barang.
     */
    public function edit(Barang $barang): View
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Memperbarui data barang yang sudah ada.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        // Validasi update mirip store, tapi rule unique SKU harus mengecualikan ID saat ini.
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:barangs,sku,' . $barang->id],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        // Update record sesuai input valid.
        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->gambar) {  
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }
}
