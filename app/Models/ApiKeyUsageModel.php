<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiKeyUsageModel extends Model
{
    protected $table = 'limits'; // Nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['uri', 'count', 'hour_started', 'api_key']; // 'api_key' sesuai dengan tabel limits

    // Fungsi untuk mendapatkan penggunaan API berdasarkan api_key dan uri
    public function getUsage($apiKey, $uri)
    {
        return $this->where('api_key', $apiKey) // Menggunakan 'api_key' sesuai dengan kolom di tabel limits
                    ->where('uri', $uri)
                    ->first();
    }

    // Fungsi untuk mengupdate count
    public function incrementUsage($apiKey, $uri)
    {
        $this->where('api_key', $apiKey) // Menggunakan 'api_key' sesuai dengan kolom di tabel limits
             ->where('uri', $uri)
             ->set('count', 'count + 1', false) // false memastikan tidak ada escaping
             ->update();
    }

    // Fungsi untuk reset penggunaan
    public function resetUsage($apiKey, $uri)
    {
        $this->where('api_key', $apiKey) // Menggunakan 'api_key' sesuai dengan kolom di tabel limits
             ->where('uri', $uri)
             ->set([
                'count' => 1,
                'hour_started' => date('Y-m-d H:i:s')
             ])
             ->update();
    }

    // Fungsi untuk membuat record baru jika belum ada
    public function createUsage($apiKey, $uri)
    {
        $this->insert([
            'api_key' => $apiKey, // Menggunakan 'api_key' sesuai dengan kolom di tabel limits
            'uri' => $uri,
            'count' => 1,
            'hour_started' => date('Y-m-d H:i:s')
        ]);
    }
}
