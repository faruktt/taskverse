<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['order_id','gig_id','buyer_id','rating','comment'];

    public function order()  { return $this->belongsTo(Order::class); }
    public function gig()    { return $this->belongsTo(Gig::class); }
    public function buyer()  { return $this->belongsTo(User::class, 'buyer_id'); }
}
