<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MahasiswaController extends ResourceController
{
    protected $modelName = 'App\Models\Mahasiswa_model';
    protected $format    = 'json';
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
{
    $id = $this->request->getVar('id'); // Mengambil id dari query parameter
    if ($id === null){
        $mahasiswa = $this->model->getMahasiswa();
    } else {
        $mahasiswa = $this->model->getMahasiswa($id);

        // Cek apakah mahasiswa dengan ID tersebut ditemukan
        if (!$mahasiswa) {
            return $this->respond([
                'status' => false,
                'message' => 'id not found'
            ], 404);  // Menggunakan status HTTP 404
        }
    }
    
    $data = [
        'message' => 'success',
        'data_mahasiswa' => $mahasiswa
    ];

    return $this->respond($data, 200);
}


    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $mahasiswa = $this->model->getMahasiswa($id);

        // Cek apakah data mahasiswa ditemukan
        if ($mahasiswa) {
            return $this->respond($mahasiswa, 200);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'id not found'
            ], 404);  // Menggunakan status HTTP 404
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = $this->validate([
            'nrp' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'jurusan' => 'required'
        ]);

        if (!$rules){
            $response = [
                'message' => $this->validator->getErrors()
            ];

            return $this->failValidationErrors($response);
        }

        $this->model->insert([
            'nrp' => esc($this->request->getVar('nrp')),
            'nama' => esc($this->request->getVar('nama')),
            'email' => esc($this->request->getVar('email')),
            'jurusan' => esc($this->request->getVar('jurusan')),
        ]);

        $response = [
            'message' => 'Data mahasiswa berhasil ditambahkan'
        ];

        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $rules = $this->validate([
            'nrp' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'jurusan' => 'required'
        ]);

        if (!$rules){
            $response = [
                'message' => $this->validator->getErrors()
            ];

            return $this->failValidationErrors($response);
        }

        $this->model->update($id, [
            'nrp' => esc($this->request->getVar('nrp')),
            'nama' => esc($this->request->getVar('nama')),
            'email' => esc($this->request->getVar('email')),
            'jurusan' => esc($this->request->getVar('jurusan')),
        ]);

        $response = [
            'message' => 'Data mahasiswa berhasil diubah'
        ];

        return $this->respond($response, 200);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->model->delete($id);

        $response = [
            'message' => 'Data mahasiswa berhasil dihapus'
        ];

        return $this->respondDeleted($response);
    }
}
