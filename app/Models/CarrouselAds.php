<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarrouselAds extends Model
{
    use HasFactory;

    protected $table = 'carrouselads';

    protected $fillable = [
        'title',
        'summary',
        'image',
        'carousel_id',
        'is_active',
        'order',
    ];
}
