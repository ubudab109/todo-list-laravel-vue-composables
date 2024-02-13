<?php

use App\Constants\TaskLevel;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title')->index();
            $table->text('description');
            $table->date('due_date')->nullable();
            $table->timestamp('date_completed')->nullable();
            $table->timestamp('archived_date')->nullable();
            $table->enum('priority', TaskLevel::toArray())->nullable();
            $table->json('tags')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
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
