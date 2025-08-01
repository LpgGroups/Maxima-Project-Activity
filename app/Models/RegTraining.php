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
        'id',
        'name_pic',
        'name_company',
        'phone_pic',
        'email_pic',
        'activity',
        'date',
        'date_end',
        'reason_fail',
        'place',
        'city',
        'provience',
        'address',
        'isprogress',
        'isfinish',
        'user_id',
    ];

    public function participants()
    {
        return $this->hasMany(RegParticipant::class, 'form_id');
    }

    public function files()
    {
        return $this->hasMany(FileRequirement::class, 'file_id');
    }

    public function trainingNotifications()
    {
        return $this->hasMany(TrainingNotification::class, 'reg_training_id');
    }

    public function isComplete()
    {
        return !empty($this->name_pic) &&
            !empty($this->name_company) &&
            !empty($this->email_pic) &&
            !empty($this->phone_pic);
    }
    public function isLinkFilled()
    {
        return $this->isprogress >= 3;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approvalFiles()
    {
        return $this->hasMany(FileRequirement::class, 'file_id', 'id');
    }
}
