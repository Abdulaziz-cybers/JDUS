<?php

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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->date('date');
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->enum('week_day',['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
            $table->tinyInteger('pair');
            $table->unique(['subject_id', 'group_id', 'date', 'pair', 'week_day', 'room_id','teacher_id'],'unique_schedule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
