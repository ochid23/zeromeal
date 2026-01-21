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

        // DEBUGGING LOGIC START
        $debugMsg = 'Login Gagal.';
        
        // Cek User
        if ($user) {
             // User Ketemu, tapi Hash salah
             $debugMsg .= ' [User: Found, Pass Hash Mismatch]';
        } else {
             $debugMsg .= ' [User: Not Found]';
        }

        // Cek Admin (Manual check for debug)
        $adminDebug = \Illuminate\Support\Facades\DB::connection('mysql_api')
                ->table('admin')
                ->where('email', $request->email)
                ->first();
        
        if ($adminDebug) {
             // Admin Ketemu
             if ($adminDebug->password !== $request->password) {
                 $debugMsg .= ' [Admin: Found, Plain Pass Mismatch]';
             }
        } else {
             $debugMsg .= ' [Admin: Not Found]';
        }
        // DEBUGGING LOGIC END

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

        try {
            // --- REFACTOR: USE API SERVICE ---
            
            // 1. Ambil Barang Hampir Kadaluarsa via API
            $expResponse = $this->apiService->get('/inventaris/hampir-kadaluarsa');
            $expiringItems = [];
            if ($expResponse->successful()) {
                // Adjust based on actual API response structure (checking 'data' or direct array)
                $data = $expResponse->json();
                $items = $data['data'] ?? $data; // Fallback
                
                if (is_array($items)) {
                    $expiringItems = collect($items)->take(5)->map(function($item) {
                        return [
                            'name' => $item['nama_barang'] ?? $item['name'] ?? 'Unknown',
                            'days_left' => $item['hari_tersisa'] ?? 0,
                            'qty' => ($item['jumlah'] ?? 0) . ' ' . ($item['satuan'] ?? '')
                        ];
                    })->toArray();
                }
            }

            // 2. Ambil Daftar Belanja via API
            $shopResponse = $this->apiService->get('/daftar-belanja');
            $shoppingList = [];
            $totalShoppingCost = 0;
            $totalShoppingItems = 0;

            if ($shopResponse->successful()) {
                $data = $shopResponse->json();
                $items = $data['data'] ?? $data;

                if (is_array($items)) {
                    $shoppingList = collect($items)->map(function($item) use (&$totalShoppingCost) {
                        // Calculate cost if available
                        $price = $item['barang']['harga'] ?? $item['harga'] ?? 0;
                        $qty = $item['jumlah_produk'] ?? 1;
                        $totalShoppingCost += $price * $qty;

                        return [
                            'name' => $item['barang']['nama_barang'] ?? $item['nama_barang'] ?? 'Item',
                            'qty' => ($item['jumlah_produk'] ?? 1) . ' ' . ($item['barang']['satuan_standar'] ?? $item['satuan'] ?? ''),
                            'checked' => ($item['status_beli'] ?? 0) == 1
                        ];
                    })->toArray();
                    $totalShoppingItems = count($shoppingList);
                }
            }

            // 3. Ambil Rekomendasi Resep via API
            $recipeResponse = $this->apiService->get('/resep/rekomendasi');
            $recipes = [];
            if ($recipeResponse->successful()) {
                $data = $recipeResponse->json();
                $items = $data['data'] ?? $data;

                if (is_array($items)) {
                    $recipes = collect($items)->take(4)->map(function($recipe) {
                         $img = $recipe['image_url'] ?? null;
                         $title = $recipe['judul'] ?? 'Recipe';
                         if (!$img || !str_contains($img, 'http')) {
                             $img = 'https://source.unsplash.com/500x300/?food,' . urlencode($title);
                         }

                         return [
                             'title' => $title,
                             'match' => $recipe['persentase_kecocokan'] ?? 0,
                             'image' => $img,
                             'deskripsi' => $recipe['deskripsi'] ?? ''
                         ];
                    })->toArray();
                }
            }

            // --- Inventory Count (Check Empty) ---
            // Use /inventaris/status or simple count if no status endpoint
            $statusResponse = $this->apiService->get('/inventaris/status');
            $totalInventory = 0;
            $totalExpiring = 0; // Can get true count from API

            if ($statusResponse->successful()) {
                $statusData = $statusResponse->json();
                // Assumed structure: ['total_items' => 10, 'expiring_soon' => 2]
                $totalInventory = $statusData['total_items'] ?? $statusData['total_inventaris'] ?? 0;
                $totalExpiring = $statusData['expiring_soon'] ?? $statusData['hampir_kadaluarsa'] ?? 0;
            } else {
                // Fallback: fetch list if status fails
                $invResponse = $this->apiService->get('/inventaris');
                if ($invResponse->successful()) {
                    $invData = $invResponse->json();
                    $invItems = $invData['data'] ?? $invData;
                    if(is_array($invItems)){
                        $totalInventory = count($invItems);
                    }
                }
                // Fallback for expiring count from list above
                $totalExpiring = count($expiringItems); 
            }
            
            $isEmptyInventory = $totalInventory === 0;

            // Fetch User Budget from API Profile if needed, or Session
            // Used user object from session which might have old budget data.
            // Ideally should fetch profile /user
            $userBudget = $userSession['budget'] ?? 0; 

            return view('dashboard', compact('expiringItems', 'shoppingList', 'recipes', 'isEmptyInventory', 
                'totalInventory', 'totalExpiring', 'totalShoppingItems', 'totalShoppingCost', 'userBudget'));

        } catch (\Exception $e) {
            // Keep debug dump for now until confirmed working
            dd('DASHBOARD API ERROR:', $e->getMessage(), $e->getTraceAsString());
        }
    }
}
