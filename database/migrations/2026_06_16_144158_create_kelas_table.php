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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('subject')->nullable();
        $table->string('room')->nullable();
        $table->string('code')->unique();
        $table->string('color')->nullable();
        $table->string('icon')->nullable();
        $table->string('teacher_name')->nullable();
        $table->string('level')->nullable();
        $table->foreignId('creator_id')->nullable();
        $table->integer('progress')->default(0);
        $table->integer('users_count')->default(0);
        $table->json('materials')->nullable();
        $table->json('tasks')->nullable();
        $table->integer('tasks_count')->default(0);
        $table->json('users')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
