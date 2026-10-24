<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'image'];

    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_items', 'collection_id', 'product_id');
    }
}
