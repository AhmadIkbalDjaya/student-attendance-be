<?php

use App\Models\Claass;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Claass::class);
            $table->foreignIdFor(Teacher::class);
            $table->foreignIdFor(Semester::class);
            $table->timestamps();

            $table->foreign("claass_id")->references("id")->on("claasses")->onDelete("cascade");
            $table->foreign("teacher_id")->references("id")->on("teachers")->onDelete("cascade");
            $table->foreign("semester_id")->references("id")->on("semesters")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
