<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class uni extends Model
{
    use HasFactory;

    // Specify the table name (Eloquent assumes 'unis' by default)
    protected $table = 'uni'; 

    // Define the primary key explicitly
    protected $primaryKey = 'uniID';
    public $incrementing = true; // Ensure auto-increment is enabled if needed
    protected $keyType = 'int'; 
    // Allow mass assignment for these fields
    protected $fillable = [
        'uniName',
        'Address',
        'ContactNumber',
        'OperationHour',
        'DateOfOpenSchool',
        'Category',
        'Description',
        'Founder',
        'EstablishDate',
        'Ranking',
        'NumOfCourses',
        'image',
    ];
    public function courses()
    {
        return $this->hasMany(Course::class, 'uniID', 'uniID');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class,'uniID','uniID');
    }
}
