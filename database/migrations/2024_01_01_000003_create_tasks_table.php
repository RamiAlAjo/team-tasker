<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('completed')->default(false);
            $table->unsignedBigInteger('status_id')->default(1); // Default to 'todo'
            $table->unsignedBigInteger('user_id');
            $table->timestamp('due_date')->nullable();
            $table->integer('priority')->default(1); // 1=low, 2=medium, 3=high
            $table->timestamps();

            // Note: Foreign key constraints commented out for MySQL compatibility
            // Uncomment when database supports them properly
            // $table->foreign('status_id')->references('id')->on('task_statuses')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};