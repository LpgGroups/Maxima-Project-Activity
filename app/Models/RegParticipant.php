<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\RegParticipantFactory> */
    use HasFactory;
    protected $table = 'reg_participant';
    protected $fillable = [
        'id',
        'name',
        'reason',
        'isprogress',
        'form_id',
    ];
}
