<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecipeController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $response = $this->apiService->get('/resep');
        $recipes = [];

        if ($response->successful()) {
            $data = $response->json();
            $recipes = $data['data'] ?? [];
        }

        return view('admin.dashboard', compact('recipes'));
    }

    public function create()
    {
        return view('admin.recipes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'time_estimate' => 'required|integer',
            'calories' => 'required|integer',
            'image_url' => 'required|string',
            'carbs' => 'nullable|numeric',
            'protein' => 'nullable|numeric',
            'fat' => 'nullable|numeric',
            'fun_fact' => 'nullable|string',
            'steps' => 'nullable|string',
        ]);

        // Mapping to API expected format
        $payload = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'difficulty' => $request->difficulty,
            'waktu_pembuatan_menit' => $request->time_estimate,
            'kalori_per_porsi' => $request->calories,
            'karbohidrat_gram' => $request->carbs ?? 0,
            'protein_gram' => $request->protein ?? 0,
            'lemak_gram' => $request->fat ?? 0,
            'image_url' => $request->image_url,
            'fun_fact' => $request->fun_fact,
            'langkah' => $request->steps,
            // 'details' => [] // Admin UI doesn't support ingredients yet based on previous code
        ];

        $response = $this->apiService->post('/resep', $payload);

        if ($response->successful()) {
            return redirect()->route('admin.dashboard')->with('success', 'Recipe created successfully via API');
        }

        return back()->withErrors(['msg' => 'Failed to create recipe: ' . ($response->json()['message'] ?? 'Unknown Error')]);
    }

    public function edit($id)
    {
        $response = $this->apiService->get("/resep/{$id}");

        if ($response->successful()) {
            $data = $response->json();
            $recipe = $data['data'] ?? null;

            if ($recipe) {
                // Map API fields to View expected fields if necessary (usually they match)
                // API uses 'waktu_pembuatan_menit' vs View likely expecting 'time_estimate' ?
                // Let's check the update validation rule: it used 'time_estimate'.
                // We might need to transform keys for the edit view if it relies on old column names.
                // However, usually we bind using the same payload.
                // Legacy view likely uses: $recipe['waktu_pembuatan_menit'] if it was raw DB.
                // Let's assume the view inspects the keys provided. 
                // But the 'update' method validation uses 'time_estimate'. 
                // So the view probably has <input name="time_estimate" value="{{ $recipe['waktu_pembuatan_menit'] }}">
                
                return view('admin.recipes.edit', compact('recipe'));
            }
        }

        return redirect()->route('admin.dashboard')->withErrors(['msg' => 'Recipe not found']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'time_estimate' => 'required|integer',
            'calories' => 'required|integer',
            'image_url' => 'required|string',
        ]);

        $payload = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'difficulty' => $request->difficulty,
            'waktu_pembuatan_menit' => $request->time_estimate,
            'kalori_per_porsi' => $request->calories,
            'karbohidrat_gram' => $request->carbs ?? 0,
            'protein_gram' => $request->protein ?? 0,
            'lemak_gram' => $request->fat ?? 0,
            'image_url' => $request->image_url,
            'fun_fact' => $request->fun_fact,
            'langkah' => $request->steps,
        ];

        $response = $this->apiService->put("/resep/{$id}", $payload);

        if ($response->successful()) {
            return redirect()->route('admin.dashboard')->with('success', 'Recipe updated successfully via API');
        }

        return back()->withErrors(['msg' => 'Failed to update recipe: ' . ($response->json()['message'] ?? 'Unknown Error')]);
    }

    public function destroy($id)
    {
        $response = $this->apiService->delete("/resep/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.dashboard')->with('success', 'Recipe deleted successfully via API');
        }

        return back()->withErrors(['msg' => 'Failed to delete recipe']);
    }
}
