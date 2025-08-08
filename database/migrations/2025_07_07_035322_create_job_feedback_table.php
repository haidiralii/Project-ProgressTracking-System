<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('feedback_note');
            $table->date('feedback_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_feedbacks');
    }
};
