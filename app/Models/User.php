<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','avatar','bio','country','phone','is_banned','is_seller_verified',
    ];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'is_seller_verified' => 'boolean',
        ];
    }

    public function isSeller(): bool { return $this->role === 'seller'; }
    public function isBuyer(): bool  { return $this->role === 'buyer'; }

    public function gigs()         { return $this->hasMany(Gig::class, 'seller_id'); }
    public function buyerOrders()  { return $this->hasMany(Order::class, 'buyer_id'); }
    public function sellerOrders() { return $this->hasMany(Order::class, 'seller_id'); }
    public function reviews()      { return $this->hasMany(Review::class, 'buyer_id'); }
    public function withdrawals()  { return $this->hasMany(Withdrawal::class); }

    public function totalEarnings(): float
    {
        return (float) $this->sellerOrders()->where('status', 'completed')->sum('price');
    }

    public function totalWithdrawn(): float
    {
        return (float) $this->withdrawals()->whereIn('status', ['completed', 'pending'])->sum('amount');
    }

    public function availableBalance(): float
    {
        return (float) max(0, $this->totalEarnings() - $this->totalWithdrawn());
    }

    public function avatarUrl(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6c63ff&color=fff&size=128';
    }
}
