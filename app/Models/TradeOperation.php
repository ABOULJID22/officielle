<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lab_id',
        'product_id',
        'challenge_start',
        'challenge_end',
        'compensation',
        'compensation_type',
        'sent_at',
        'via',
        'contract_path',
        'received',
        'photos',
        'attachments',
    ];

    protected $casts = [
        'challenge_start' => 'date',
        'challenge_end' => 'date',
        'sent_at' => 'date',
        'received' => 'boolean',
        'compensation' => 'decimal:2',
        'photos' => 'array',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_trade_operation');
    }

    public function getProductNamesAttribute(): string
    {
        $list = $this->relationLoaded('products') ? $this->products : $this->products()->get();
        if ($list->isNotEmpty()) {
            return $list->pluck('name')->implode(', ');
        }
        return $this->product?->name ?? '';
    }

    // photos stored as array of paths
}
