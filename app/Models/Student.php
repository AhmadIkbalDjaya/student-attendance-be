<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function claass()
    {
        return $this->belongsTo(Claass::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    public function attendaces()
    {
        return $this->belongsToMany(Attendance::class, 'student_attendances')->withPivot('status_id');
    }
}
