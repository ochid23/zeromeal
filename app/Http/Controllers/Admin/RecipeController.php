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

        // Simulating middleware
        if (!Session::has('admin_token')) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    public function index()
    {
        $recipes = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('resep')
            ->get();

        // Convert to array to support view's array syntax ($recipe['key'])
        $recipes = json_decode(json_encode($recipes), true);

        return view('admin.dashboard', compact('recipes'));
    }

    public function create()
    {
        return view('admin.recipes.create');
    }

    public function store(Request $request)
    {
        // Validation matches DB schema
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'time_estimate' => 'required|integer',
            'calories' => 'required|integer',
            'image_url' => 'required|string', // Assuming URL input for now as per previous code
            'carbs' => 'nullable|numeric',
            'protein' => 'nullable|numeric',
            'fat' => 'nullable|numeric',
            'fun_fact' => 'nullable|string',
            'steps' => 'nullable|string',
        ]);

        // Resolve Admin ID from Session Token
        $adminId = 1; // Default fallback
        if (Session::has('admin_token')) {
            $tokenPayload = base64_decode(Session::get('admin_token'));
            $parts = explode(':', $tokenPayload);
            $email = $parts[0] ?? null;

            if ($email) {
                $admin = \Illuminate\Support\Facades\DB::connection('mysql_api')
                    ->table('admin')
                    ->where('email', $email)
                    ->first();
                if ($admin) {
                    $adminId = $admin->admin_id;
                }
            }
        }

        try {
            \Illuminate\Support\Facades\DB::connection('mysql_api')->table('resep')->insert([
                'admin_id' => $adminId,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'difficulty' => $request->difficulty,
                'image_url' => $request->image_url,
                'waktu_pembuatan_menit' => $request->time_estimate,
                'kalori_per_porsi' => $request->calories,
                'karbohidrat_gram' => $request->carbs ?? 0,
                'protein_gram' => $request->protein ?? 0,
                'lemak_gram' => $request->fat ?? 0,
                'fun_fact' => $request->fun_fact,
                'langkah' => $request->steps,
                'rating' => 0.0, // Default rating
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Recipe created successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Create Recipe Failed: ' . $e->getMessage());
            return back()->withErrors(['msg' => 'Failed to create recipe: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $recipe = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('resep')
            ->where('resep_id', $id)
            ->first();

        if ($recipe) {
            // Transform stdClass to array if view expects array, or keep as object. 
            // Views usually handle both, but let's confirm usage in edit.blade.php later.
            // For now assuming object access in view ($recipe->judul) or array ($recipe['judul'])
            // Decoding to array to be safe if view uses array syntax
            $recipe = (array) $recipe;
            return view('admin.recipes.edit', compact('recipe'));
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

        try {
            \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('resep')
                ->where('resep_id', $id)
                ->update([
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'difficulty' => $request->difficulty,
                    'image_url' => $request->image_url,
                    'waktu_pembuatan_menit' => $request->time_estimate,
                    'kalori_per_porsi' => $request->calories,
                    'karbohidrat_gram' => $request->carbs ?? 0,
                    'protein_gram' => $request->protein ?? 0,
                    'lemak_gram' => $request->fat ?? 0,
                    'fun_fact' => $request->fun_fact,
                    'langkah' => $request->steps,
                ]);

            return redirect()->route('admin.dashboard')->with('success', 'Recipe updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Failed to update recipe: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('resep')
                ->where('resep_id', $id)
                ->delete();

            return redirect()->route('admin.dashboard')->with('success', 'Recipe deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Failed to delete recipe']);
        }
    }
}
