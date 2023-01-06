<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $fillable = ['user_name', 'email', 'gender', 'created_at'];
    public $timestamps = false;
}