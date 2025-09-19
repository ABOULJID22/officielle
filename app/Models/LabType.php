<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabType extends Model
{
    use HasFactory;

    protected $fillable = ['lab_category_id','name'];

    public function category()
    {
        return $this->belongsTo(LabCategory::class, 'lab_category_id');
    }
}
