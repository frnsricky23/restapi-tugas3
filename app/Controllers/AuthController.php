<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
  protected $format = 'json';

  // Secret key untuk JWT, disarankan menggunakan environment variables
  private $secretKey;

  public function __construct()
  {
    // Mendapatkan secret key dari environment variables
    $this->secretKey = getenv('JWT_SECRET_KEY') ?: 'your-default-secret-key';
  }

  public function login($username, $password)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/auth/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    // Menggunakan http_build_query untuk mengonversi array menjadi string
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
      'username' => $username,
      'password' => $password
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    $response = curl_exec($ch);
    $data = json_decode($response, true);

    if (isset($data['data']['token'])) {
      $this->token = $data['data']['token'];
      return view('login_result', ['message' => 'Login successful, token: ' . $this->token]);
    } else {
      return view('login_result', ['message' => 'Login failed: ' . $data['message']]);
    }

    curl_close($ch);
  }


  // Fungsi untuk memvalidasi kredensial (diubah untuk memeriksa dari DB atau sumber lainnya)
  private function validateCredentials($username, $password)
  {
    // Implementasi kredensial dengan DB atau sistem autentikasi lainnya
    // Pada contoh ini, kita hanya memeriksa nilai hardcoded
    return ($username === 'admin' && $password === '215314127');
  }
}
