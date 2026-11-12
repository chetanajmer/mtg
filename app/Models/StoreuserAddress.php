<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreuserAddress extends Model
{
    use HasFactory;

    protected $table = 'storeuser_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'name',
        'mobile',
        'location',
        'address',
        'city',
        'country',
        'is_default',
    ];
}
