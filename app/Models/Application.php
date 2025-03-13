<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\uni;

class Application extends Model {

    use HasFactory;

    protected $fillable = [
        'lastName',
        'firstName',
        'ic',
        'BirthDayDate',
        'gender',
        'certificate',
        'fileInput',
        'address',
        'address2',
        'address3',
        'phone',
        'education',
        'nationality',
        'otherNationality',
        'university',
        'status'
    ];

    public function universityContent() {
        return $this->belongsTo(uni::class, 'university', 'id');
    }
}
