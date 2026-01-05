<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => $result['message'],
        ]);
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
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:50',
            'password' => 'required|confirmed|min:8',
            'no_telepon' => 'required|string|max:15',
        ]);

        $result = $this->apiService->register($request->only('nama', 'email', 'password', 'no_telepon', 'password_confirmation'));

        if ($result['success']) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => $result['message'],
        ])->withInput($request->except('password', 'password_confirmation'));
    }

    public function logout()
    {
        // Optional: Call API logout endpoint if it exists
        // $this->apiService->post('/logout');

        Session::forget(['api_token', 'user']);
        Session::flush();
        
        return redirect()->route('login');
    }

  public function dashboard()
    {
        // Panggil API Backend
        $response = $this->apiService->get('/dashboard-data');

        // Data Default
        $expiringItems = [];
        $shoppingList = [];
        $recipes = [];

        // --- TAMBAHKAN BARIS DI BAWAH INI ---
        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->successful()) {
            $data = $response->json()['data'];
            $expiringItems = $data['expiringItems'] ?? [];
            $shoppingList = $data['shoppingList'] ?? [];
            $recipes = $data['recipes'] ?? [];
        }

        // Cek apakah inventory kosong untuk trigger onboarding
        // Kita panggil API inventaris untuk hitung total item
        $inventoryResponse = $this->apiService->get('/inventaris');
        $isEmptyInventory = false;
        
        if ($inventoryResponse->successful()) {
            $inventoryData = $inventoryResponse->json()['data'] ?? [];
            $isEmptyInventory = count($inventoryData) === 0;
        }

        return view('dashboard', compact('expiringItems', 'shoppingList', 'recipes', 'isEmptyInventory'));
    }
}
