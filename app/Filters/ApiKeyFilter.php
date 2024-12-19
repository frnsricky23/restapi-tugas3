<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\ApiKeyModel;
use App\Models\ApiKeyUsageModel;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil API key dari header
        $apiKey = $request->getHeaderLine('x-api-key');
        $uri = $request->getUri()->getPath();

        // Cek apakah API key kosong
        if (empty($apiKey)) {
            return $this->respondWithError(401, 'Unauthorized, missing API key');
        }

        // Cek API key di database
        $apiKeyModel = new ApiKeyModel();
        $keyData = $apiKeyModel->getApiKey($apiKey);

        if (!$keyData) {
            // Jika API key tidak valid, kembalikan respon error
            return $this->respondWithError(401, 'Unauthorized, invalid API key');
        }

        // Cek limit dari database
        $usageModel = new ApiKeyUsageModel();
        $usage = $usageModel->getUsage($apiKey, $uri);

        $currentTime = time();
        if ($usage) {
            $currentCount = $usage['count'];
            $hourStarted = strtotime($usage['hour_started']);

            // Batasi waktu menjadi 1 jam
            if (($currentTime - $hourStarted) < 3600) { // Jika masih dalam waktu 1 jam
                if ($currentCount >= 3) {
                    return $this->respondWithError(429, 'Limit exceeded, try again later.');
                } else {
                    // Update count
                    $usageModel->incrementUsage($apiKey, $uri);
                }
            } else {
                // Reset count jika sudah lebih dari 1 jam
                $usageModel->resetUsage($apiKey, $uri);
            }
        } else {
            // Jika belum ada data limit untuk URI dan API key ini, buat baru
            $usageModel->createUsage($apiKey, $uri);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Implementasi opsional jika dibutuhkan
    }

    // Fungsi untuk mengirimkan respon error
    private function respondWithError(int $statusCode, string $message)
    {
        return \Config\Services::response()
            ->setStatusCode($statusCode)
            ->setJSON(['status' => $statusCode, 'message' => $message]);
    }
}
