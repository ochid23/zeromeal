<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingList;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Ambil data belanja DAN data barang terkait (nama, satuan, kategori)
        $list = ShoppingList::with('barang')
            ->where('user_id', $user->user_id)
            ->get();
            
        return response()->json(['success' => true, 'data' => $list]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Validasi input sesuai kolom database
        $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah_produk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric'
        ]);

        ShoppingList::create([
            'user_id' => $user->user_id,
            'barang_id' => $request->barang_id,
            'jumlah_produk' => $request->jumlah_produk,
            'harga' => $request->harga ?? 0, // Default 0 jika kosong
            'status_beli' => 0
        ]);

        return response()->json(['success' => true]);
    }

    public function toggle(Request $request, $id)
    {
        // Cari berdasarkan belanja_id
        $item = ShoppingList::where('belanja_id', $id)->first();
        
        if ($item) {
            $item->status_beli = !$item->status_beli; // Toggle 0 ke 1, atau 1 ke 0
            $item->save();
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        // Hapus berdasarkan belanja_id
        ShoppingList::destroy($id);
        return response()->json(['success' => true]);
    }
}