<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Config\Services;

class JWTAuthFilter implements FilterInterface
{
    private $secretKey = 'your-secret-key';

    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil token dari header Authorization
        $authHeader = $request->getHeaderLine('Authorization');
        if (empty($authHeader)) {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'message' => 'Authorization header is missing']);
        }

        // Extract token
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
        } else {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'message' => 'Bearer token is missing']);
        }

        try {
            // Decode JWT
            $decoded = JWT::decode($jwt, $this->secretKey, ['HS256']);
            $request->userData = (object) $decoded;  // Set user data into request
        } catch (\Exception $e) {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'message' => 'Invalid or expired token']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Optional: nothing to do after the response is sent
    }
}
