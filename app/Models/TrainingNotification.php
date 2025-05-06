<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_training_id',
        'user_id',
        'viewed_at',
    ];

    public function training()
    {
        return $this->belongsTo(RegTraining::class, 'reg_training_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
