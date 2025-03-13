<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'uniID', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
{
    return $this->belongsTo(uni::class, 'uniID','uniID');
}
}

