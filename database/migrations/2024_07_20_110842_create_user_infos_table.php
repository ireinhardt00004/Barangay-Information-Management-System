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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('uid')->unique();
            $table->string('profile_pic')->nullable();
            $table->string('valid_id')->nullable();
            $table->string('phone_number', 11)->default('');
            $table->enum('sex', ['N/A','Male', 'Female']);
            $table->string('address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('req_no')->nullable();
            $table->boolean('active')->default(0);
            $table->string('reset_hash')->nullable();
            $table->enum('first_time_seeker', ['Yes', 'No'])->nullable();
            $table->json('data')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
