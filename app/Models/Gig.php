<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    protected $fillable = [
        'seller_id','category_id','title','description','thumbnail',
        'price_basic','price_standard','price_premium',
        'basic_desc','standard_desc','premium_desc',
        'delivery_days','revisions','tags','status',
        'views','avg_rating','total_reviews','total_orders',
    ];

    public function seller()   { return $this->belongsTo(User::class, 'seller_id'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function images()   { return $this->hasMany(GigImage::class)->orderBy('sort_order'); }
    public function orders()   { return $this->hasMany(Order::class); }
    public function reviews()  { return $this->hasMany(Review::class); }

    public function thumbnailUrl(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/gig-placeholder.jpg');
    }
}
