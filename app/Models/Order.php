<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number','gig_id','buyer_id','seller_id','package','price',
        'delivery_days','status','payment_status','requirements','delivery_note','due_at',
    ];

    protected $casts = ['due_at' => 'datetime'];

    public static function generateNumber(): string
    {
        return 'TV-' . strtoupper(Str::random(8));
    }

    public function gig()          { return $this->belongsTo(Gig::class); }
    public function buyer()        { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller()       { return $this->belongsTo(User::class, 'seller_id'); }
    public function conversation() { return $this->hasOne(Conversation::class); }
    public function review()       { return $this->hasOne(Review::class); }

    public function statusBadge(): array
    {
        return match($this->status) {
            'pending'     => ['label' => 'Pending',     'color' => '#fdcb6e'],
            'in_progress' => ['label' => 'In Progress', 'color' => '#74b9ff'],
            'delivered'   => ['label' => 'Delivered',   'color' => '#a29bfe'],
            'revision'    => ['label' => 'Revision',    'color' => '#fd79a8'],
            'completed'   => ['label' => 'Completed',   'color' => '#00cec9'],
            'cancelled'   => ['label' => 'Cancelled',   'color' => '#ff7675'],
            default       => ['label' => ucfirst($this->status), 'color' => '#b2bec3'],
        };
    }
}
