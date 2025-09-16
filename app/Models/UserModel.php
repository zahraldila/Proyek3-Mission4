<?php namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table      = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username','password_hash','role','full_name','created_at','updated_at'];
}
