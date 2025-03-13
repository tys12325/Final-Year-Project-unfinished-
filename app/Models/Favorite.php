<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Favorite extends Model
{
     use HasFactory;

    protected $fillable = ['user_id', 'courseID'];
    
    
    public function course()
{
    return $this->belongsTo(Course::class, 'courseID','courseID');
}
  
}
