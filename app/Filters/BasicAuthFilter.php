<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class BasicAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $username = 'admin'; // Contoh username
        $password = '215314127'; // Contoh password

        // Mendapatkan header Authorization
        $header = $request->getServer('HTTP_AUTHORIZATION');
        if (!empty($header) && preg_match('/Basic\s+(.*)$/i', $header, $matches)) {
            list($name, $pass) = explode(':', base64_decode($matches[1]), 2);
            if ($name === $username && $pass === $password) {
                return; // Autentikasi berhasil
            }
        }

        return \Config\Services::response()
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
            ->setJSON(['error' => 'Unauthorized, invalid credentials']);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Opsional
    }
}
