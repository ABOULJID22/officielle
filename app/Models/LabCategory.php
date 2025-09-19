<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabCategory extends Model
{
    use HasFactory;

    protected $fillable = ['lab_id','name'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function types()
    {
        return $this->hasMany(LabType::class, 'lab_category_id');
    }
}
