<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigImage extends Model
{
    protected $fillable = ['gig_id','path','sort_order'];

    public function gig() { return $this->belongsTo(Gig::class); }

    public function url(): string { return asset('storage/' . $this->path); }
}
