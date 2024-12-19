<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa_model extends Model{
    protected $table = 'mahasiswa'; // Nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['nrp', 'nama', 'email', 'jurusan'];

    public function getMahasiswa($id = null)
{
    if ($id === null){
        return $this->findAll(); // Mengambil semua data
    } else {
        return $this->find($id); // Mengambil data berdasarkan ID
    }
}

}