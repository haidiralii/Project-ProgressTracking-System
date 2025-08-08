<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('requested_at')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', [
                'buat_baru',
                'pengerjaan',
                'tunda',
                'tes',
                'perbaikan',
                'selesai'
            ])->default('buat_baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
