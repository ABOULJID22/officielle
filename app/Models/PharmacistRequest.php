<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacistRequest extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id','status','message','admin_note','approved_at','rejected_at',
        'applicant_name','applicant_email','phone','pharmacy_name','pharmacy_address'
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
