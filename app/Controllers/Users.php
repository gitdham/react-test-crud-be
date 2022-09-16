<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

helper('json_helper');

class Users extends ResourceController {
    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index() {
        $users = $this->userModel->findAll();

        $resBody = [
            'success' => true,
            'message' => 'User fetch successfully',
            'data' => [
                'user' => $users,
                'errors' => '',
            ],
        ];
        return $this->respond($resBody);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null) {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new() {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create() {
        $formFields = $this->request->getJSON(true);
        $newUser = new User($formFields);

        try {
            $saved = $this->userModel->save($newUser);
            if (!$saved) {
                $err = json_encode($this->userModel->errors());
                throw new Exception($err);
            }

            $resBody = [
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'user' => $newUser,
                    'errors' => '',
                ],
            ];
            return $this->respond($resBody, 201);
        } catch (Exception $err) {
            if (isJson($err->getMessage()))
                $errors = json_decode($err->getMessage(), true);

            $resBody = [
                'success' => false,
                'message' => 'Failed to create user',
                'data' => [
                    'user' => $newUser,
                    'errors' => $errors ?? $err->getMessage(),
                ]
            ];

            return $this->respond($resBody, 400);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null) {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null) {
        $formFields = $this->request->getJSON(true);

        try {
            $user = $this->userModel->find($id);
            if (!$user) throw new Exception('User not found');

            $user->fill($formFields);
            unset($user->token);

            $saved = $this->userModel->save($user);
            if (!$saved) {
                $err = json_encode($this->userModel->errors());
                throw new Exception($err);
            }

            $resBody = [
                'success' => true,
                'message' => 'User updated successfully',
            ];
            return $this->respond($resBody, 201);
        } catch (Exception $err) {
            if (isJson($err->getMessage()))
                $errors = json_decode($err->getMessage(), true);

            $resBody = [
                'success' => false,
                'message' => 'Failed to update user',
                'data' => [
                    'errors' => $errors ?? $err->getMessage(),
                ]
            ];

            return $this->respond($resBody, 400);
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null) {
        try {
            $user = $this->userModel->find($id);
            if (!$user) throw new Exception('User not found');

            $this->userModel->delete($id);

            $resBody = [
                'success' => true,
                'message' => 'User deleted successfully',
            ];
            return $this->respond($resBody, 201);
        } catch (Exception $err) {
            if (isJson($err->getMessage()))
                $errors = json_decode($err->getMessage(), true);

            $resBody = [
                'success' => false,
                'message' => 'Failed to delete user',
                'data' => [
                    'errors' => $errors ?? $err->getMessage(),
                ]
            ];

            return $this->respond($resBody, 400);
        }
    }
}
