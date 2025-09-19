<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lab_id',
        'lab_category_id',
        'lab_type_id',
        'type',
        'commercial_id',
        'last_order_date',
        'last_order_value',
        'next_order_date',
        'annual_target',
        'status',
        'attachments',
    ];

    protected $casts = [
        'last_order_date' => 'date',
        'next_order_date' => 'date',
        'last_order_value' => 'decimal:2',
        'annual_target' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function labCategory()
    {
        return $this->belongsTo(LabCategory::class, 'lab_category_id');
    }

    public function labType()
    {
        return $this->belongsTo(LabType::class, 'lab_type_id');
    }

    public function commercial()
    {
        return $this->belongsTo(Commercial::class);
    }
}
