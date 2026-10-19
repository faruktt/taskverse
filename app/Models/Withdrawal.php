<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'amount_bdt',
        'method',
        'account_details',
        'status',
        'note',
        'admin_note',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'amount_bdt' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
