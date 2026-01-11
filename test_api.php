<?php

function testEndpoint($path)
{
    $url = 'http://127.0.0.1:8001/api' . $path;
    $data = ['email' => 'hidayat@zeromeal.com', 'password' => 'admin123'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "----------------------------------------\n";
    echo "TESTING: $url\n";
    echo "HTTP Code: $httpCode\n";
    if ($error)
        echo "Error: $error\n";
    echo "Response: $response\n";
    echo "----------------------------------------\n";
}

testEndpoint('/admin/login');
testEndpoint('/auth/admin/login'); // Guessing common patterns
testEndpoint('/login'); // Generic login
testEndpoint('/admin-login'); // Another guess
