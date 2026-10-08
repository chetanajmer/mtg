<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storeuser extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'fname',
        'lname',
        'password','socialid','socialaccount'
    ];
    
}
