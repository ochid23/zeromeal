<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Ambil user yang sedang login

        // 1. Ambil Barang Hampir Kadaluarsa (Dari View SQL Anda)
        // Menggunakan view: vw_notifikasi_kadaluarsa
        $expiringItems = DB::table('vw_inventaris_status')
            ->where('user_id', $user->user_id)
            ->where('hari_tersisa', '<=', 7) // Tampilkan semua yang H-7
            ->orderBy('hari_tersisa', 'asc')
            ->select('nama_barang as name', 'hari_tersisa as days_left', DB::raw("CONCAT(jumlah, ' ', satuan) as qty"))
            ->limit(5)
            ->get();

        // 2. Ambil Daftar Belanja
        $shoppingList = DB::table('daftar_belanja')
            ->join('barang', 'daftar_belanja.barang_id', '=', 'barang.barang_id')
            ->where('daftar_belanja.user_id', $user->user_id)
            ->select('barang.nama_barang as name', DB::raw("CONCAT(daftar_belanja.jumlah_produk, ' ', barang.satuan_standar) as qty"), 'daftar_belanja.status_beli as checked')
            ->get()
            ->map(function($item) {
                $item->checked = $item->checked == 1; // Convert 1/0 to true/false
                return $item;
            });

        // 3. Ambil Rekomendasi Resep (Dari View SQL Cerdas Anda)
        // Menggunakan view: vw_resep_rekomendasi
        $recipes = DB::table('vw_resep_rekomendasi')
            ->where('user_id', $user->user_id) // Filter view berdasarkan user
            ->select('judul as title', 'persentase_kecocokan as match', 'image_url as image', 'deskripsi')
            ->orderByDesc('persentase_kecocokan')
            ->limit(4)
            ->get()
            ->map(function($recipe) {
                // Tambahkan waktu random jika tidak ada di view, dan fix image path
                $recipe->time = rand(15, 45) . ' min';
                // Jika image_url cuma nama file, kita anggap placeholder dulu
                if (!str_contains($recipe->image, 'http')) {
                    $recipe->image = 'https://source.unsplash.com/500x300/?food,' . urlencode($recipe->title); 
                }
                return $recipe;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'expiringItems' => $expiringItems,
                'shoppingList' => $shoppingList,
                'recipes' => $recipes
            ]
        ]);
    }
}