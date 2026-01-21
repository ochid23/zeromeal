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

        $result = $this->apiService->login($request->email, $request->password);

        if ($result['success']) {
            // Check Onboarding
            $user = Session::get('user');
            // Ensure $user is an object
            if (is_array($user)) {
                $user = (object) $user;
            }
            
            if (empty($user->preferensi)) {
                return redirect()->route('onboarding');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => $result['message'] ?? 'Login failed.',
        ]);
    }

    public function showOnboarding()
    {
        try {
            // FIX: Force clear route cache to resolve persistent 404s on deployment
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            
            return view('onboarding');
        } catch (\Exception $e) {
            // If cache clearing fails, still try to show the view
            \Illuminate\Support\Facades\Log::error('Force Cache Clear Failed', ['error' => $e->getMessage()]);
            return view('onboarding');
        }
    }

    public function storePreferences(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'goal' => 'required|string',
            'cooking_frequency' => 'required|string',
        ]);

        $userSession = Session::get('user');
        // Handle object or array
        $userId = is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);
        
        if (!$userId) return redirect()->route('login');

        $preferences = json_encode($request->only('source', 'goal', 'cooking_frequency'));

        // Call API to update user
        // Call API to update user
        // Endpoint: /magic-save (No /api prefix) - BYPASS FIX
        $response = $this->apiService->get("/magic-save", [
            'preferensi' => $preferences
        ]);

        if ($response->successful()) {
            // Update session user
            $updatedUser = $response->json()['data'] ?? $userSession;
             
            // If response is just success status but not full user, merge manually
            if (is_array($userSession)) {
                $userSession['preferensi'] = $preferences;
            } else {
                $userSession->preferensi = $preferences;
            }
            // Use updated user from API if available and looks like a user object
            if (isset($updatedUser['user_id'])) {
                 Session::put('user', $updatedUser);
            } else {
                 Session::put('user', $userSession);
            }

            return redirect()->route('dashboard');
        }
        
        // Log error if needed but graceful fail
        \Illuminate\Support\Facades\Log::error('Onboarding API Error', ['status' => $response->status(), 'body' => $response->body()]);
        
        // DEBUG: Force user to see the error (Restored for debugging)
        dd('STATUS DEPLOYMENT BARU:', $response->status(), $response->body());

        return back()->with('error', 'Gagal menyimpan preferensi. Silakan coba lagi.');
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
            'email' => 'required|email|max:50',
            'password' => 'required|confirmed|min:8',
            'no_telepon' => 'required|string|max:15',
        ]);

        $data = $request->only('nama', 'email', 'password', 'password_confirmation', 'no_telepon');
        $result = $this->apiService->register($data);

        if ($result['success']) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => $result['message'] ?? 'Registration failed.',
        ])->withInput($request->except('password', 'password_confirmation'));
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
        
        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = $request->password;
            $data['current_password'] = $request->current_password;
        }

        // Call API
        $response = $this->apiService->get("/magic-save", $data);

        if ($response->successful()) {
             $responseData = $response->json();
             // Update Session
             $updatedUser = $responseData['data'] ?? $responseData['user'] ?? null;
             
             if ($updatedUser) {
                 Session::put('user', $updatedUser);
             } else {
                 // Fallback update session manually if API doesn't return user
                 if (is_array($userSession)) {
                     $userSession['nama'] = $request->nama;
                     $userSession['email'] = $request->email;
                 } else {
                     $userSession->nama = $request->nama;
                     $userSession->email = $request->email;
                 }
                 Session::put('user', $userSession);
             }

             return back()->with('success', 'Profil berhasil diperbarui!');
        }

        $errorMsg = $response->json()['message'] ?? 'Gagal memperbarui profil.';
        return back()->withErrors(['email' => $errorMsg]);
    }

    public function dashboard()
    {
        // Ambil User ID dari Session
        $userSession = Session::get('user');
        $userId = is_array($userSession) ? ($userSession['user_id'] ?? null) : ($userSession->user_id ?? null);
        $userToken = Session::get('api_token');

        if (!$userId || !$userToken) {
            return redirect()->route('login')->with('error', 'Sesi kadaluarsa, silakan login kembali.');
        }

        try {
            // --- FE: CALL API /dashboard ---
            // Assuming the API has a dashboard endpoint that returns aggregated data.
            // If not, we call individual endpoints.
            
            // 1. Expiring Items
            $expResponse = $this->apiService->get('/inventaris'); // Fetch all to filter or use specific endpoint if exists
            $expiringItems = [];
            $isEmptyInventory = true;
            $totalInventory = 0;

            if ($expResponse->successful()) {
                $invData = $expResponse->json()['data'] ?? [];
                $totalInventory = count($invData);
                $isEmptyInventory = $totalInventory === 0;
                
                // Filter expiring <= 7 days
                $expiringItems = collect($invData)->filter(function($item) {
                    // Logic to check date diff, assuming API returns 'days_left' or 'tanggal_kadaluarsa'
                    // For now, if API returns raw data, we might need to calculate.
                    // Let's assume API returns 'hari_tersisa' as per previous DB view
                    return isset($item['hari_tersisa']) && $item['hari_tersisa'] <= 7;
                })->sortBy('hari_tersisa')->take(5)->values()->all();
            }

            // 2. Shopping List
            $shopResponse = $this->apiService->get('/daftar-belanja');
            $shoppingList = [];
            $totalShoppingCost = 0;
            $totalShoppingItems = 0;

            if ($shopResponse->successful()) {
                $shopData = $shopResponse->json()['data'] ?? [];
                $shoppingList = $shopData;
                $totalShoppingItems = count($shoppingList);
                // Calculate cost if price exists
                 $totalShoppingCost = collect($shoppingList)->sum(function($item) {
                     return ($item['harga'] ?? 0) * ($item['jumlah_produk'] ?? 1);
                 });
            }

            // 3. Recipes
            $recipeResponse = $this->apiService->get('/resep'); // Or /resep/rekomendasi
            $recipes = [];
            
            if ($recipeResponse->successful()) {
                $recipeData = $recipeResponse->json()['data'] ?? [];
                // Simple take 4 for dashboard
                $recipes = collect($recipeData)->take(4)->all();
            }
            
            // Summary Metrics
            $totalExpiring = count($expiringItems);
            $userBudget = is_array($userSession) ? ($userSession['budget'] ?? 0) : ($userSession->user_id ?? 0);

            try {
                return view('dashboard', compact('expiringItems', 'shoppingList', 'recipes', 'isEmptyInventory', 
                    'totalInventory', 'totalExpiring', 'totalShoppingItems', 'totalShoppingCost', 'userBudget'));
            } catch (\Exception $e) {
                dd('DASHBOARD VIEW ERROR:', $e->getMessage(), $e->getTraceAsString());
            }

        } catch (\Exception $e) {
             return redirect()->route('login')->with('error', 'Gagal memuat dashboard. Silakan login ulang.');
        }
    }
}
