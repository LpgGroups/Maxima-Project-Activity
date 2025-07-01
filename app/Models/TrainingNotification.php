<?php

namespace App\Models;

use App\Notifications\TrainingUpdatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

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
    public function notifications()
    {
        return DatabaseNotification::query()
            ->where('type', TrainingUpdatedNotification::class)
            ->whereJsonContains('data->training_id', $this->reg_training_id);
    }
}
