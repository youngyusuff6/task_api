<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'no_of_credits', 'ref_no', 'status'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
