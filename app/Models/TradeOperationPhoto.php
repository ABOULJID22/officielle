<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOperationPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_operation_id',
        'path',
    ];

    public function tradeOperation()
    {
        return $this->belongsTo(TradeOperation::class);
    }
}
