<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    
   protected $fillable = [
        'user_id',
        'filters1',
        'filters2',
        'filters3',
        'filters4',
        'rating',
        'comment'
    ];
}

