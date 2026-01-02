<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url', env('API_BASE_URL'));
    }

    /**
     * Get the headers for the request, including the Bearer token if available.
     */
    protected function getHeaders()
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if (Session::has('api_token')) {
            $headers['Authorization'] = 'Bearer ' . Session::get('api_token');
        }

        return $headers;
    }

    /**
     * Handle login specifically to store the token.
     */
    public function login($email, $password)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Assumes the API returns 'token' or 'access_token'
            $token = $data['token'] ?? $data['access_token'] ?? null;
            $user = $data['user'] ?? null;

            if ($token) {
                Session::put('api_token', $token);
                if ($user) {
                    Session::put('user', $user);
                }
                return ['success' => true, 'data' => $data];
            }
        }

        return ['success' => false, 'message' => $response->json()['message'] ?? 'Login failed'];
    }

    public function get($endpoint, $params = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . $endpoint, $params);
    }

    public function post($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . $endpoint, $data);
    }

    public function put($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->put($this->baseUrl . $endpoint, $data);
    }

    public function delete($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->delete($this->baseUrl . $endpoint, $data);
    }
}
