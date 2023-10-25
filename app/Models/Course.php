<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function claass()
    {
        return $this->belongsTo(Claass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'course_students');
    }
}
