<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Auth extends ResourceController {
    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        $formFields = $this->request->getJSON(true);

        try {
            $user = $this->userModel->where('email', $formFields['email'])->first();

            if (!$user || !password_verify($formFields['password'], $user->password))
                throw new Exception('Invalid email / password');

            $resBody = [
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'email' => $user->email,
                    'token' => 'aabbcc',
                ],
            ];
            return $this->respond($resBody);
        } catch (Exception $err) {
            $resBody = [
                'success' => false,
                'message' => $err->getMessage(),
            ];

            return $this->respond($resBody, 400);
        }
    }
}
