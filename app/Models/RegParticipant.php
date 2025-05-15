<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\RegParticipantFactory> */
    use HasFactory;
    protected $table = 'reg_participants';
    protected $fillable = [
        'id',
        'name',
        'status',
        'reason',
        'isprogress',
        'form_id',
    ];
}
