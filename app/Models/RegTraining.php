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
        'link',
        'place',
        'isprogress',
        'user_id',
    ];

    public function participants()
    {
        return $this->hasMany(RegParticipant::class, 'form_id');
    }

    public function files(){
        return $this->hasMany(FileRequirement::class,'file_id');
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
        return !empty($this->link);
    }

}

