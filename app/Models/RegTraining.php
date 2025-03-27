<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegTraining extends Model
{
    /** @use HasFactory<\Database\Factories\RegTrainingFactory> */
    use HasFactory;
    protected $table = 'reg_training';

    protected $fillable = [
        'name_pic',
        'name_company',
        'phone',
        'email',
        'activity',
        'date',
        'place',
        'user_id',
    ];
}

