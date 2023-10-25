<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function status()
    {
        return $this->belongsTo(AttendanceStatus::class);
    }
}
