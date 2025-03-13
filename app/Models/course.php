<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'courseID';
    protected $fillable = [
        'courseName',
        'duration',
        'feesLocal',
        'feesInternational',
        'studyType',
        'studyLevel',
        'uniID',
    ];

    // Define the relationship: A course belongs to a university
    public function university()
    {
        return $this->belongsTo(Uni::class, 'uniID', 'uniID');
    }
}
