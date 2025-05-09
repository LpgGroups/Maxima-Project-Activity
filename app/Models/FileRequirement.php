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
        'file_mou',
        'file_quotation',
        'file_id',
    ];
}
