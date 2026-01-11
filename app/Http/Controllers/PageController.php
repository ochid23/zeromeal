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
        // 1. Ambil Data Stok Inventory (Join dengan Barang)
        $inventory = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('inventaris_barang')
            ->join('barang', 'inventaris_barang.barang_id', '=', 'barang.barang_id')
            ->select('inventaris_barang.*', 'barang.nama_barang', 'barang.kategori', 'barang.satuan', 'barang.gambar')
            ->orderBy('inventaris_barang.tanggal_kadaluarsa', 'asc')
            ->get();

        // Convert to array
        $inventory = json_decode(json_encode($inventory), true);

        // 2. Ambil Master Data Barang
        $masterBarang = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('barang')
            ->select('barang_id', 'nama_barang', 'kategori', 'satuan', 'satuan_standar')
            ->orderBy('nama_barang')
            ->get();
        $masterBarang = json_decode(json_encode($masterBarang), true);

        return view('inventory', compact('inventory', 'masterBarang'));
    }

    public function shoppingList()
    {
        // 1. Ambil Daftar Belanja (Join dengan Barang)
        $shoppingList = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->join('barang', 'daftar_belanja.barang_id', '=', 'barang.barang_id')
            ->select('daftar_belanja.*', 'barang.nama_barang', 'barang.kategori', 'barang.satuan_standar')
            ->orderBy('daftar_belanja.belanja_id', 'desc')
            ->get();

        $shoppingList = json_decode(json_encode($shoppingList), true);

        // 2. Ambil Master Barang
        $masterBarang = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('barang')
            ->select('barang_id', 'nama_barang', 'satuan_standar')
            ->orderBy('nama_barang')
            ->get();
        $masterBarang = json_decode(json_encode($masterBarang), true);

        return view('shopping_list', compact('shoppingList', 'masterBarang'));
    }

    public function storeShoppingItem(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'jumlah_produk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric'
        ]);

        \Illuminate\Support\Facades\DB::connection('mysql_api')->table('daftar_belanja')->insert([
            'barang_id' => $request->barang_id,
            'jumlah_produk' => $request->jumlah_produk,
            'harga' => $request->harga ?? 0,
            'status_beli' => 0,
            'user_id' => 1 // Default user
        ]);

        return redirect()->back()->with('success', 'Barang ditambahkan ke daftar belanja.');
    }

    public function updateShoppingItem(Request $request, $id)
    {
        $request->validate([
            'jumlah_produk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric'
        ]);

        \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->where('belanja_id', $id)
            ->update([
                'jumlah_produk' => $request->jumlah_produk,
                'harga' => $request->harga ?? 0
            ]);

        return redirect()->back()->with('success', 'Item belanja berhasil diperbarui.');
    }

    public function shoppingDelete($id)
    {
        \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->where('belanja_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Item dihapus dari daftar belanja.');
    }

    public function shoppingToggle($id)
    {
        $item = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->where('belanja_id', $id)
            ->first();

        if ($item) {
            \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('daftar_belanja')
                ->where('belanja_id', $id)
                ->update(['status_beli' => !$item->status_beli]);
        }

        return redirect()->back();
    }

    public function storeInventory(Request $request)
    {
        $request->validate([
            'barang_id' => 'nullable|integer',
            'nama_barang_baru' => 'nullable|string|max:50',
            'jumlah' => 'required|numeric|min:1',
            'lokasi' => 'required|string',
            'harga' => 'nullable|numeric',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $barangId = $request->barang_id;

        // Handle Input Barang Baru
        if (!$barangId && $request->nama_barang_baru) {
            $barangId = \Illuminate\Support\Facades\DB::connection('mysql_api')->table('barang')->insertGetId([
                'nama_barang' => $request->nama_barang_baru,
                'kategori' => $request->kategori_baru ?? 'Lainnya',
                'satuan' => $request->satuan_baru ?? 'pcs',
                'satuan_standar' => $request->satuan_baru ?? 'pcs',
                'harga' => $request->harga ?? 0,
                'umur_penyimpanan' => 7 // Default
            ]);
        }

        if (!$barangId) {
            return back()->with('error', 'Silakan pilih barang atau masukkan nama barang baru.');
        }

        \Illuminate\Support\Facades\DB::connection('mysql_api')->table('inventaris_barang')->insert([
            'user_id' => 1,
            'barang_id' => $barangId,
            'jumlah' => $request->jumlah,
            'lokasi_penyimpanan' => $request->lokasi,
            'harga' => $request->harga ?? 0,
            'tanggal_pembelian' => $request->tanggal_pembelian ?? now(),
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'catatan' => $request->catatan
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke Pantry!');
    }

    public function deleteInventory($id)
    {
        \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('inventaris_barang')
            ->where('inventaris_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Barang dihapus dari stok.');
    }

    public function updateInventory(Request $request, $id)
    {
        // For simple update logic
        $data = [
            'jumlah' => $request->jumlah,
            'lokasi_penyimpanan' => $request->lokasi,
            'harga' => $request->harga,
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'catatan' => $request->catatan,
        ];

        // If changing barang (advanced), logic would be needed, but assume just property update for now
        \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('inventaris_barang')
            ->where('inventaris_id', $id)
            ->update($data);

        return redirect()->back()->with('success', 'Data inventaris diperbarui.');
    }

    // ... (Recipes methods remain unchanged from previous step, but included here for context if needed, 
    // actually I should wrap this carefully to not overwrite the recipes methods at the bottom if I used EndLine correctly.)
    // Wait, the ReplacementContent replaces everything from inventory() onwards. 
    // I MUST include the existing recipe methods in my ReplacementContent or they will be lost if I replace up to line 230.
    // End of Inventory & Shopping List methods
    // Continuing with existing Recipe methods

    public function recipes()
    {
        // Fetch directly from MySQL to match Admin Dashboard
        $recipes = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('resep')
            ->get();

        // Convert to array
        $recipes = json_decode(json_encode($recipes), true);

        $favorites = session('favorites', []);
        return view('recipes', compact('recipes', 'favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $id = $request->input('id');
        $favorites = session('favorites', []);

        if (in_array($id, $favorites)) {
            $favorites = array_diff($favorites, [$id]); // Remove
        } else {
            $favorites[] = $id; // Add
        }

        session(['favorites' => $favorites]);

        return response()->json(['status' => 'success', 'is_favorite' => in_array($id, $favorites)]);
    }

    public function favoriteRecipes()
    {
        $favoriteIds = session('favorites', []);

        if (empty($favoriteIds)) {
            $favorites = [];
        } else {
            $favorites = \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('resep')
                ->whereIn('resep_id', $favoriteIds)
                ->get();

            // Convert to array
            $favorites = json_decode(json_encode($favorites), true);
        }

        return view('favorite-recipes', compact('favorites'));
    }

    public function finance()
    {
        return view('finance');
    }
}