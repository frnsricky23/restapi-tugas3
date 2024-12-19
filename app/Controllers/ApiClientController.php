<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ApiClientController extends Controller
{
    private $apiUrl = 'http://localhost/api'; // Ganti dengan URL API Anda
    private $token;

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
            echo 'Login successful, token: ' . $this->token;
        } else {
            echo 'Login failed: ' . $data['message'];
        }

        curl_close($ch);
    }

    // Fungsi untuk mengakses data mahasiswa dengan token
    public function getMahasiswa()
    {
        if (!$this->token) {
            echo 'Token missing, please login first.';
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/mahasiswa');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token
        ]);

        $response = curl_exec($ch);
        $data = json_decode($response, true);

        if ($data['status'] === 'success') {
            // Menampilkan data mahasiswa dalam format yang lebih mudah dibaca
            echo '<pre>';
            print_r($data['data_mahasiswa']);
            echo '</pre>';
        } else {
            echo 'Error: ' . $data['message'];
        }

        curl_close($ch);
    }

    // Endpoint untuk login
    public function loginEndpoint()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $this->login($username, $password);
    }

    // Endpoint untuk mendapatkan data mahasiswa
    public function getMahasiswaEndpoint()
    {
        $this->getMahasiswa();
    }
}
