<?php

use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Attendance::class);
            $table->foreignIdFor(Student::class);
            $table->foreignIdFor(AttendanceStatus::class, 'status_id');
            $table->string('image')->nullable()->default(null);
            $table->timestamps();

            $table->foreign("attendance_id")->references("id")->on("attendances")->onDelete("cascade");
            $table->foreign("student_id")->references("id")->on("students")->onDelete("cascade");
            $table->foreign("status_id")->references("id")->on("attendance_statuses")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
