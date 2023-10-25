<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    
    public function student_attendances() {
        return $this->hasMany(StudentAttendance::class);
    }
}
