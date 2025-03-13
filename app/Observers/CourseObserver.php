<?php
namespace App\Observers;

use App\Models\course;
use App\Models\Uni;

class CourseObserver
{
    public function created(course $course)
    {
        $this->updateNumOfCourses($course->uniID);
    }

    public function deleted(course $course)
    {
        $this->updateNumOfCourses($course->uniID);
    }

    private function updateNumOfCourses($uniID)
    {
        $count = course::where('uniID', $uniID)->count();
        Uni::where('uniID', $uniID)->update(['NumOfCourses' => $count]);
    }
}
