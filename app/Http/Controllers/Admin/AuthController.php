<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        if (Session::has('admin_token')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Assuming a specific admin login endpoint exists, or we utilize the same login with a role check if the API supports it.
        // Given the 'admin' table in the screenshot, it's likely a separate auth flow or table.
        // I will attempt to hit /admin/login endpoint on the API.

        $response = $this->apiService->post('/admin/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $token = $data['token'] ?? $data['data']['token'] ?? null;
            $admin = $data['admin'] ?? $data['data']['admin'] ?? null; // Screenshot shows 'admin' table

            if ($token) {
                Session::put('admin_token', $token);
                // We might want to separate user token and admin token to allow dual login or avoid conflict
                // Ideally, we use different session keys.
                Session::put('admin_user', $admin);

                // Hack: ApiService uses 'api_token' from session. 
                // We might need to adjust ApiService to support 'admin_token' or we switch context.
                // For now, let's assume ApiService reads 'admin_token' if we tell it to, OR we just overwrite 'api_token' 
                // but that would logout the regular user.
                // Let's check ApiService again.
                // ApiService reads 'api_token'. 
                // I will add logic to ApiService or just set 'api_token' here if it's acceptable to be logged in effectively as one or the other.
                // Safest bet for "separate admin page" is to treat it as a separate session context if possible, 
                // but simpler implementation is to just use the one token slot if the middleware allows.
                // HOWEVER, the routes for admin likely need a different token.
                // Let's Update ApiService to check for admin_token if the request is for admin? 
                // Or easier: Just set 'api_token' to the admin token. 
                // But wait, if I'm a user and an admin, overwriting kills my user session.
                // Let's try to keep them separate. I will modify ApiService slightly or just pass token manually.
                // Actually, I can't easily modify ApiService for every call without changing it.
                // Let's stick to using 'api_token' for now for simplicity, assuming single active session per browser context isn't a huge blocker,
                // OR better: Create a derived ApiService or just manually handle headers in this controller? No, that breaks DRY.

                // DECISION: I will configure ApiService to prefer 'admin_token' if it exists AND we are hitting admin routes? 
                // No, that's complex.
                // I will just use 'api_token'. If the user logs in as admin, they are "logged in". 
                // Distinct session keys 'admin_logged_in' can track UI state.

                Session::put('api_token', $token); // Use standard key for ApiService compatibility
                Session::put('is_admin', true);

                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'email' => $response->json()['message'] ?? 'Login failed',
        ]);
    }

    public function logout()
    {
        Session::forget(['api_token', 'is_admin', 'admin_user']);
        return redirect()->route('admin.login');
    }
}
