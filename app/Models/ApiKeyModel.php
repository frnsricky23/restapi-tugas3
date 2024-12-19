<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiKeyModel extends Model
{
    protected $table = 'keys'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['id', 'user_id', 'key', 'level', 'ignore_limits', 'is_private_key', 'ip_addresses', 'date_created'];

    // Fungsi untuk mendapatkan API key dari tabel keys
    public function getApiKey($apiKey)
    {
        return $this->where('key', $apiKey)->first(); // Menggunakan 'key' sesuai dengan kolom di tabel keys
    }
}
