<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- WAJIB ADA
use App\Models\InventarisBarang;
use App\Models\Barang;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $inventory = DB::table('vw_inventaris_status')
            ->where('user_id', $user->user_id)
            ->orderBy('hari_tersisa', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $inventory]);
    }

    public function masterData()
    {
        $barang = Barang::select('barang_id', 'nama_barang', 'satuan_standar', 'kategori')->get();
        return response()->json(['success' => true, 'data' => $barang]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $barangId = $request->barang_id;
        
        // Variabel untuk menentukan umur simpan default (dalam hari)
        $umurSimpan = 30; // Default 30 hari

        // LOGIKA 1: JIKA INPUT BARANG BARU
        if (empty($barangId) && $request->filled('nama_barang_baru')) {
            $existingBarang = Barang::where('nama_barang', $request->nama_barang_baru)->first();

            if ($existingBarang) {
                $barangId = $existingBarang->barang_id;
                // Ambil umur simpan dari barang yg sudah ada (jika ada datanya)
                // Jika tidak, kita tentukan berdasarkan kategori nanti
            } else {
                $newBarang = new Barang();
                $newBarang->nama_barang = $request->nama_barang_baru;
                $newBarang->kategori = $request->kategori_baru;
                $newBarang->satuan_standar = $request->satuan_baru;
                $newBarang->harga = 0; 

                // Tentukan umur penyimpanan untuk Master Barang
                if (in_array($request->kategori_baru, ['Sayuran', 'Protein', 'Buah'])) {
                    $newBarang->umur_penyimpanan = '7 hari';
                    $umurSimpan = 7;
                } else {
                    $newBarang->umur_penyimpanan = '30 hari';
                    $umurSimpan = 30;
                }
                
                $newBarang->save();
                $barangId = $newBarang->barang_id;
            }
        } 
        // LOGIKA 2: JIKA PILIH DARI LIST YANG ADA
        else {
            // Cek kategori barang tersebut untuk tentukan expired otomatis
            $barang = Barang::find($barangId);
            if ($barang) {
                if (in_array($barang->kategori, ['Sayuran', 'Protein', 'Buah'])) {
                    $umurSimpan = 7;
                }
            }
        }

        if (!$barangId) {
            return response()->json(['success' => false, 'message' => 'Barang tidak valid'], 400);
        }

        // HITUNG TANGGAL KADALUARSA OTOMATIS
        // Tambahkan hari ini + umur simpan
        $tanggalExpired = now()->addDays($umurSimpan);

        // Simpan ke Inventaris User
        InventarisBarang::create([
            'user_id' => $user->user_id,
            'barang_id' => $barangId,
            'jumlah' => $request->jumlah,
            'lokasi_penyimpanan' => $request->lokasi,
            'tanggal_pembelian' => now(),
            // PENTING: Kita simpan tanggal expired yang sudah dihitung
            'tanggal_kadaluarsa' => $tanggalExpired, 
        ]);

        return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan']);
    }

    // --- BAGIAN DELETE YANG DIPERBAIKI ---
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        // 1. Cek apakah User ID terbaca
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terdeteksi.'], 401);
        }

        // Gunakan getKey() untuk memastikan kita dapat ID user yang benar, apapun setting modelnya
        $userId = $user->getKey(); 

        // 2. Hapus langsung di Database
        $deleted = DB::table('inventaris_barang')
            ->where('user_id', $userId)
            ->where('inventaris_id', $id)
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Barang berhasil dihapus']);
        } else {
            // Beri pesan detail untuk debugging
            return response()->json([
                'success' => false, 
                'message' => "Gagal: Barang ID $id milik User ID $userId tidak ditemukan."
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Validasi input
        // Kita izinkan user mengganti barang_id (misal salah pilih barang)
        // Dan izinkan set manual tanggal kadaluarsa
        
        $item = InventarisBarang::where('user_id', $user->user_id)
            ->where('inventaris_id', $id)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }

        // Update data
        // Jika user mengirim tanggal_kadaluarsa manual, kita pakai itu.
        // Jika tidak, biarkan data lama atau trigger yang bekerja.
        
        $updateData = [
            'jumlah' => $request->jumlah,
            'lokasi_penyimpanan' => $request->lokasi,
        ];

        // Jika user mengganti jenis barang
        if ($request->filled('barang_id')) {
            $updateData['barang_id'] = $request->barang_id;
        }

        // Fitur Spesial: Edit Tanggal Expired Manual
        if ($request->filled('tanggal_kadaluarsa')) {
            $updateData['tanggal_kadaluarsa'] = $request->tanggal_kadaluarsa;
        }

        $item->update($updateData);

        return response()->json(['success' => true, 'message' => 'Data barang berhasil diperbarui']);
    }
}