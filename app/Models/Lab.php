<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function tradeOperations()
    {
        return $this->hasMany(TradeOperation::class);
    }

    public function categories()
    {
        return $this->hasMany(LabCategory::class);
    }
}
