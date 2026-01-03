<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\Response; 

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url', env('API_BASE_URL'));
    }

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

    public function login($email, $password)
    {
        /** @var Response $response */
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/login', [
            'email' => $email,
            'password' => $password,
        ]);

        \Illuminate\Support\Facades\Log::info('Login Response:', ['status' => $response->status(), 'body' => $response->json()]);

        if ($response->successful()) {
            $responseData = $response->json();
            
            $token = $responseData['token'] ?? $responseData['access_token'] ?? $responseData['data']['token'] ?? null;
            $user = $responseData['user'] ?? $responseData['data']['user'] ?? null;

            if ($token) {
                Session::put('api_token', $token);
                if ($user) {
                    Session::put('user', $user);
                }
                return ['success' => true, 'data' => $responseData];
            }
        }

        return ['success' => false, 'message' => $response->json()['message'] ?? 'Login failed'];
    }

    public function register($data)
    {
        /** @var Response $response */
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/register', $data);

        if ($response->successful()) {
            $responseData = $response->json();
            
            $token = $responseData['token'] ?? $responseData['access_token'] ?? $responseData['data']['token'] ?? null;
            $user = $responseData['user'] ?? $responseData['data']['user'] ?? null;

            if ($token) {
                Session::put('api_token', $token);
                if ($user) {
                    Session::put('user', $user);
                }
                return ['success' => true, 'data' => $responseData];
            }
        }

        return ['success' => false, 'message' => $response->json()['message'] ?? 'Registration failed'];
    }

   /**
     * @return Response
     */
    public function get($endpoint, $params = [])
    {
        /** @var Response */
        return Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . $endpoint, $params);
    }

    /**
     * @return Response
     */
    public function post($endpoint, $data = [])
    {
        /** @var Response */
        return Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . $endpoint, $data);
    }

    /**
     * @return Response
     */
    public function put($endpoint, $data = [])
    {
        /** @var Response */
        return Http::withHeaders($this->getHeaders())
            ->put($this->baseUrl . $endpoint, $data);
    }

    /**
     * @return Response
     */
    public function delete($endpoint, $data = [])
    {
        /** @var Response */
        return Http::withHeaders($this->getHeaders())
            ->delete($this->baseUrl . $endpoint, $data);
    }
}