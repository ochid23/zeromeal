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

    private function getUserId()
    {
        $userSession = \Illuminate\Support\Facades\Session::get('user');
        return is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);
    }

    public function inventory()
    {
        $response = $this->apiService->get('/inventaris');
        $inventory = [];
        $masterBarang = [];

        if ($response->successful()) {
            $inventory = $response->json()['data'] ?? [];
        }

        // Master Barang (usually separate endpoint or hardcoded for now if API doesn't support)
        // For now, let's assume we can get it or simpler view
        // API fallback for master barang
        $masterResponse = $this->apiService->get('/barang');
        if ($masterResponse->successful()) {
             $masterBarang = $masterResponse->json()['data'] ?? [];
        }

        return view('inventory', compact('inventory', 'masterBarang'));
    }

    public function shoppingList()
    {
        $response = $this->apiService->get('/daftar-belanja');
        $shoppingList = [];
        $masterBarang = [];

        if ($response->successful()) {
            $shoppingList = $response->json()['data'] ?? [];
        }
        
         $masterResponse = $this->apiService->get('/barang');
        if ($masterResponse->successful()) {
             $masterBarang = $masterResponse->json()['data'] ?? [];
        }

        return view('shopping_list', compact('shoppingList', 'masterBarang'));
    }

    public function storeShoppingItem(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'jumlah_produk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric'
        ]);

        $this->apiService->post('/daftar-belanja', $request->all());

        return redirect()->back()->with('success', 'Barang ditambahkan ke daftar belanja.');
    }

    public function updateShoppingItem(Request $request, $id)
    {
         $request->validate([
            'jumlah_produk' => 'required|integer|min:1',
            'harga' => 'nullable|numeric'
        ]);
        
        $this->apiService->put("/daftar-belanja/{$id}", $request->all());

        return redirect()->back()->with('success', 'Item belanja berhasil diperbarui.');
    }

    public function shoppingDelete($id)
    {
        $this->apiService->delete("/daftar-belanja/{$id}");
        return redirect()->back()->with('success', 'Item dihapus dari daftar belanja.');
    }

    public function shoppingToggle($id)
    {
        // Assuming API has toggle endpoint or we update status
        $this->apiService->put("/daftar-belanja/{$id}/toggle");
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

        $this->apiService->post('/inventaris', $request->all());

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke Pantry!');
    }

    public function deleteInventory($id)
    {
        $this->apiService->delete("/inventaris/{$id}");
        return redirect()->back()->with('success', 'Barang dihapus dari stok.');
    }

    public function updateInventory(Request $request, $id)
    {
        $this->apiService->put("/inventaris/{$id}", $request->all());
        return redirect()->back()->with('success', 'Data inventaris diperbarui.');
    }

    public function recipes()
    {
        $response = $this->apiService->get('/resep');
        $recipes = [];
        if ($response->successful()) {
            $recipes = $response->json()['data'] ?? [];
        }

        $favorites = session('favorites', []);
        return view('recipes', compact('recipes', 'favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $id = $request->input('id');
        $favorites = session('favorites', []);

        if (in_array($id, $favorites)) {
            $favorites = array_diff($favorites, [$id]); 
        } else {
            $favorites[] = $id; 
        }

        session(['favorites' => $favorites]);
        return response()->json(['status' => 'success', 'is_favorite' => in_array($id, $favorites)]);
    }

    public function favoriteRecipes()
    {
        // Ideally API handles this, but sticking to session-based local filtering of API data for now if API doesn't have /favorites endpoint
        $favoriteIds = session('favorites', []);
        // Fetch all (or proper endpoint)
         $response = $this->apiService->get('/resep');
         $favorites = [];
         
         if ($response->successful()) {
             $allRecipes = $response->json()['data'] ?? [];
             $favorites = collect($allRecipes)->whereIn('resep_id', $favoriteIds)->values()->all();
         }

        return view('favorite-recipes', compact('favorites'));
    }

    public function getRecipeDetails($id)
    {
        $response = $this->apiService->get("/resep/{$id}");
        
        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['status' => 'error', 'message' => 'Recipe not found'], 404);
    }

    public function finance()
    {
        return view('finance');
    }
}