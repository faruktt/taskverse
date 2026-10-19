<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','icon','color','is_active'];

    public function gigs() { return $this->hasMany(Gig::class); }
}
