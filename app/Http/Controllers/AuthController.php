<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLoginForm()
    {
        if (Session::has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Refactor: Direct DB Authentication to avoid Guzzle Timeout
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Generate Token via Sanctum (Same as API)
            // Optional: delete old tokens
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            Session::put('api_token', $token);
            Session::put('user', $user); // Store User Object/Model

            // Check Onboarding
            if (empty($user->preferensi)) {
                return redirect()->route('onboarding');
            }

            return redirect()->route('dashboard');
        }

        // 2. Try Admin Login (Fallback - Direct MySQL Check)
        // Since API might not handle admin table, we check directly.
        try {
            $admin = \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('admin')
                ->where('email', $request->email)
                ->first();

            if ($admin && $admin->password === $request->password) {
                $token = base64_encode($admin->email . ':' . time());
                Session::put('admin_token', $token);
                Session::put('api_token', $token); 
                return redirect()->route('admin.dashboard');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Direct DB Login Error: ' . $e->getMessage());
        }

        $debugMsg = 'Email atau password salah.';
        if ($user) {
            $debugMsg .= ' (User Found, Hash Check Failed. DB Pass: ' . substr($user->password, 0, 10) . '...)';
        } else {
            $debugMsg .= ' (User Not Found)';
        }

        return back()->withErrors([
            'email' => $debugMsg,
        ]);
    }

    public function showOnboarding()
    {
        return view('onboarding');
    }

    public function storePreferences(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'goal' => 'required|string',
            'cooking_frequency' => 'required|string',
        ]);

        $userSession = Session::get('user');
        $userId = is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);
        
        if (!$userId) return redirect()->route('login');

        $preferences = json_encode($request->only('source', 'goal', 'cooking_frequency'));

        $user = User::find($userId);
        $user->preferensi = $preferences;
        $user->save();
        
        // Update session user to include new preference
        Session::put('user', $user);

        return redirect()->route('dashboard');
    }

    public function showRegisterForm()
    {
        if (Session::has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:users', // Added unique check
            'password' => 'required|confirmed|min:8',
            'no_telepon' => 'required|string|max:15',
        ]);

        // Refactor: Direct DB Registration
        try {
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telepon' => $request->no_telepon,
                'budget' => 0, // Default budget
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            Session::put('api_token', $token);
            Session::put('user', $user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Registration failed: ' . $e->getMessage(),
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function logout()
    {
        // Optional: Call API logout endpoint if it exists
        // $this->apiService->post('/logout');

        Session::forget(['api_token', 'user', 'admin_token']);
        Session::flush();

        return redirect()->route('login');
    }



    public function updateProfile(Request $request)
    {
        $userSession = Session::get('user');
        $userId = is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);

        if (!$userId) return redirect()->route('login');

        // Note: 'users' table email unique check might need modification if ignoring current user, 
        // but since we don't have standard Laravel Auth::id() for 'unique:users,email,id', 
        // and we use a custom table/model, we'll do a simple check or rely on 'unique:users' if it works with our model.
        // For simplicity with custom setup:
        // Validation
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:50', 
            'password' => 'nullable|confirmed|min:8',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = 'required';
        }

        $request->validate($rules);

        $user = User::find($userId);
        
        // Manual unique email check
        $existingUser = User::where('email', $request->email)->where('user_id', '!=', $userId)->first();
        if ($existingUser) {
            return back()->withErrors(['email' => 'Email sudah digunakan pengguna lain.']);
        }

        // Verify Current Password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->nama = $request->nama;
        $user->email = $request->email;
        
        $user->save();

        // Update Session
        Session::put('user', $user);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function dashboard()
    {
        // Ambil User ID dari Session (karena login via API Service simpan di session)
        $userSession = Session::get('user');
        // Handle jika user session object atau array
        $userId = is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi kadaluarsa, silakan login kembali.');
        }

        // --- GANTIKAN API CALL /dashboard-data DENGAN DIRECT QUERY ---
        
        // 1. Ambil Barang Hampir Kadaluarsa
        $expiringItems = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('vw_inventaris_status')
            ->where('user_id', $userId)
            ->where('hari_tersisa', '<=', 7)
            ->orderBy('hari_tersisa', 'asc')
            ->select('nama_barang as name', 'hari_tersisa as days_left', \Illuminate\Support\Facades\DB::raw("CONCAT(jumlah, ' ', satuan) as qty"))
            ->limit(5)
            ->get();
        // Convert to array
        $expiringItems = json_decode(json_encode($expiringItems), true);

        // 2. Ambil Daftar Belanja
        $shoppingList = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->join('barang', 'daftar_belanja.barang_id', '=', 'barang.barang_id')
            ->where('daftar_belanja.user_id', $userId)
            ->select('barang.nama_barang as name', \Illuminate\Support\Facades\DB::raw("CONCAT(daftar_belanja.jumlah_produk, ' ', barang.satuan_standar) as qty"), 'daftar_belanja.status_beli as checked')
            ->get()
            ->map(function($item) {
                // Convert checked status same as API
                $item->checked = $item->checked == 1; 
                return $item;
            });
        $shoppingList = json_decode(json_encode($shoppingList), true);

        // 3. Ambil Rekomendasi Resep
        $recipes = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('vw_resep_rekomendasi')
            ->where('user_id', $userId)
            ->select('judul as title', 'persentase_kecocokan as match', 'image_url as image', 'deskripsi')
            ->orderByDesc('persentase_kecocokan')
            ->limit(4)
            ->get()
            ->map(function($recipe) {
                $recipe->time = rand(15, 45) . ' min';
                if (!str_contains($recipe->image, 'http')) {
                    $recipe->image = 'https://source.unsplash.com/500x300/?food,' . urlencode($recipe->title); 
                }
                return $recipe;
            });
        $recipes = json_decode(json_encode($recipes), true);

        // --- GANTIKAN API CALL /inventory (Check Empty) ---
        $inventoryCount = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('vw_inventaris_status')
            ->where('user_id', $userId)
            ->count();
        
        $isEmptyInventory = $inventoryCount === 0;

        // --- DASHBOARD SUMMARY METRICS ---
        $totalInventory = $inventoryCount;
        
        $totalExpiring = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('vw_inventaris_status')
            ->where('user_id', $userId)
            ->where('hari_tersisa', '<=', 7)
            ->count();

        $shoppingListCollection = collect($shoppingList);
        $totalShoppingItems = $shoppingListCollection->count();
        // Assuming price is total or unit price? Database table has 'harga' and 'jumlah_produk'.
        // Step 45 migration says: decimal('harga', 10, 2).
        // Let's assume 'harga' is unit price.
        // Wait, the $shoppingList query in step 39 joins 'barang' but selects everything.
        // 'daftar_belanja' also has 'harga'.
        // Let's calculate based on what we have.
        // In Step 39: $shoppingList select raw CONCAT... qty.
        // Wait, Step 39 code for shoppingList was:
        /*
          $shoppingList = ... select('barang.nama_barang as name', \DB::raw("CONCAT(daftar_belanja.jumlah_produk, ' ', barang.satuan_standar) as qty"), 'daftar_belanja.status_beli as checked')
        */
        // This query in Step 39 DOES NOT return 'harga' or 'jumlah_produk' as numbers suitable for calculation! It returns formatted strings.
        // I need to change the shopping list query to get raw numbers first, then format them, OR create a separate summary query.
        // Creating a clean separate summary calculation is safer to avoid breaking the view which expects specific format.
        
        // Actually, let's just re-query for the summary stats to be safe and accurate.
        $shoppingStats = \Illuminate\Support\Facades\DB::connection('mysql_api')
            ->table('daftar_belanja')
            ->where('user_id', $userId)
            ->select(\Illuminate\Support\Facades\DB::raw('SUM(harga * jumlah_produk) as total_cost'), \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_items'))
            ->first();

        $totalShoppingCost = $shoppingStats->total_cost ?? 0;
        $totalShoppingItems = $shoppingStats->total_items ?? 0;

        $userBudget = \App\Models\User::find($userId)->budget ?? 0;

        return view('dashboard', compact('expiringItems', 'shoppingList', 'recipes', 'isEmptyInventory', 
            'totalInventory', 'totalExpiring', 'totalShoppingItems', 'totalShoppingCost', 'userBudget'));
    }
}
