<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['name', 'email', 'marital_status', 'dob', 'role', 'designation', 'photo', 'status'];

}
