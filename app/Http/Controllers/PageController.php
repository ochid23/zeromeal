<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function inventory()
    {
        // 1. Ambil Data Stok Inventory
        $invResponse = $this->apiService->get('/inventaris');
        if ($invResponse->successful()) {
            $inventory = $invResponse->json()['data'] ?? [];
            // Log first item to check structure
            \Illuminate\Support\Facades\Log::debug('INVENTORY_ITEM_STRUCTURE: ' . json_encode($inventory[0] ?? 'EMPTY'));
        } else {
            $inventory = [];
        }

        // 2. Ambil Master Data Barang (Untuk Pilihan Dropdown)
        $masterResponse = $this->apiService->get('/barang');
        $masterBarang = $masterResponse->successful() ? ($masterResponse->json()['data'] ?? []) : [];

        // Kirim keduanya ke View
        return view('inventory', compact('inventory', 'masterBarang'));
    }

public function shoppingList()
    {
        // 1. Ambil Daftar Belanja
        $responseList = $this->apiService->get('/daftar-belanja');
        $shoppingList = $responseList->successful() ? $responseList->json()['data'] : [];

        // 2. Ambil Master Barang (Untuk Dropdown Pilihan)
        $responseMaster = $this->apiService->get('/barang');
        $masterBarang = $responseMaster->successful() ? $responseMaster->json()['data'] : [];
        
        return view('shopping_list', compact('shoppingList', 'masterBarang'));
    }

    // Update method storeShoppingItem agar mengirim data yang sesuai
    public function storeShoppingItem(Request $request)
    {
        $this->apiService->post('/daftar-belanja', [
            'barang_id' => $request->barang_id,
            'jumlah_produk' => $request->jumlah_produk,
            'harga' => $request->harga
        ]);
        return redirect()->back();
    }

    public function storeInventory(Request $request)
    {
        // Validasi: Harus ada barang_id ATAU nama_barang_baru
        $request->validate([
            'barang_id' => 'nullable|integer', // Bisa kosong jika input baru
            'nama_barang_baru' => 'nullable|string|max:50', // Wajib jika barang_id kosong
            'kategori_baru' => 'nullable|string',
            'satuan_baru' => 'nullable|string',
            'jumlah' => 'required|numeric|min:1',
            'lokasi' => 'required|string',
            'harga' => 'nullable|numeric',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        // Kirim semua data ke API (API yang akan memilah mana yang dipakai)
        $response = $this->apiService->post('/inventaris', [
            'barang_id' => $request->barang_id,
            'nama_barang_baru' => $request->nama_barang_baru,
            'kategori_baru' => $request->kategori_baru,
            'satuan_baru' => $request->satuan_baru,
            'jumlah' => $request->jumlah,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'catatan' => $request->catatan,
        ]);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
        }

        \Illuminate\Support\Facades\Log::error('Gagal Tambah Inventory:', [
            'status' => $response->status(),
            'body' => $response->body(),
            'request_data' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Gagal menambahkan barang. API Error: ' . $response->status());
    }

    public function deleteInventory($id)
    {
        // Panggil API Delete
        $response = $this->apiService->delete('/inventaris/' . $id);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Barang telah dihapus (Habis).');
        }

        // PERBAIKAN: Ambil pesan error asli dari API untuk ditampilkan
        $errorMessage = $response->json()['message'] ?? 'Terjadi kesalahan pada Server/API.';
        return redirect()->back()->with('error', 'Gagal: ' . $errorMessage);
    }
    public function updateInventory(Request $request, $id)
    {
        // Kirim data update ke API
        $response = $this->apiService->put('/inventaris/' . $id, [
            'barang_id' => $request->barang_id,
            'nama_barang_baru' => $request->nama_barang_baru,
            'kategori_baru' => $request->kategori_baru,
            'satuan_baru' => $request->satuan_baru,
            'jumlah' => $request->jumlah,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'catatan' => $request->catatan,
        ]);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data.');
    }
    public function recipes()
    {
        // Kita gunakan data dummy dulu atau ambil dari API dashboard jika ada endpoint khusus
        // Untuk sekarang, kita simulasi data resep yang lebih lengkap
        $recipes = [
            [
                'title' => 'Nasi Goreng Spesial', 
                'match' => 90, 
                'time' => '15 min',
                'image' => 'https://images.unsplash.com/photo-1603133872878-684f57d063f1?w=500&auto=format&fit=crop&q=60',
                'desc' => 'Nasi goreng lezat dengan bumbu rahasia dan topping telur.'
            ],
            [
                'title' => 'Sayur Sop Ayam', 
                'match' => 75, 
                'time' => '30 min',
                'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&auto=format&fit=crop&q=60',
                'desc' => 'Sop ayam hangat cocok untuk cuaca dingin.'
            ],
            [
                'title' => 'Telur Dadar Padang', 
                'match' => 100, 
                'time' => '10 min',
                'image' => 'https://images.unsplash.com/photo-1598679253544-2c97992403ea?w=500&auto=format&fit=crop&q=60',
                'desc' => 'Telur dadar tebal khas rumah makan Padang.'
            ],
            [
                'title' => 'Ayam Kecap Mentega', 
                'match' => 85, 
                'time' => '45 min',
                'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500&auto=format&fit=crop&q=60',
                'desc' => 'Ayam goreng dengan saus kecap mentega yang gurih manis.'
            ],
        ];

        return view('recipes', compact('recipes'));
    }
}