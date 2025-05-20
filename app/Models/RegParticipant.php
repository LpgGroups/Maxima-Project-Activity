<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RegParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\RegParticipantFactory> */
    use HasFactory;
    use Notifiable;
    protected $table = 'reg_participants';
    protected $fillable = [
        'id',
        'name',
        'status',
        'reason',
        'isprogress',
        'form_id',
    ];

    public function training()
    {
        return $this->belongsTo(RegTraining::class, 'form_id');
    }
}
