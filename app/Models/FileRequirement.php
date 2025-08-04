<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileRequirement extends Model
{
    use HasFactory;
    protected $table = 'file_requirements';
    protected $fillable = [
        'id',
        'file_approval',
        'proof_payment',
        'budget_plan',
        'letter_implementation',
        'file_nobatch',
        'note',
        'file_id',
    ];

    public function training()
    {
        return $this->belongsTo(RegTraining::class, 'id');
    }
}
