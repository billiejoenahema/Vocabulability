<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'correct_answer',
        'example',
    ];

    protected $casts = [
        'word' => 'string',
        'correct_answer' => 'string',
        'example' => 'string',
    ];
}
