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
        // Example: Fetch user data from API if needed
        // $user = $this->apiService->get('/user');
        
        return view('dashboard');
    }
}
