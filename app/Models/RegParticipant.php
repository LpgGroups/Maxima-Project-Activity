<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RegParticipant extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'reg_participants';

    protected $fillable = [
        'name',
        'nik',
        'date_birth',
        'blood_type',
        'photo',
        'ijazah',
        'letter_employee',
        'letter_statement',
        'form_registration',
        'letter_health',
        'cv',
        'reason',
        'status',
        'form_id',
    ];
    protected $touches = ['training'];

    public function training()
    {
        return $this->belongsTo(RegTraining::class, 'form_id');
    }
}
