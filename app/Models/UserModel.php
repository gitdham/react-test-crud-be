<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table            = 'users';
    protected $returnType       = \App\Entities\User::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;

    // Dates
    // protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        // 'name' => 'required',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required',
        // 'pass_confirm' => 'required_with[password]|matches[password]',
    ];
    // protected $validationMessages   = [];
}
