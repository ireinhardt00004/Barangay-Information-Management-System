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
        Schema::create('general_confs', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('title');
            $table->text('meta_desc')->nullable();
            $table->string('head_title')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_desc')->nullable();
            $table->string('gcash_no')->nullable();
            $table->integer('payment_amt')->nullable();
            $table->string('theme')->nullable();
            $table->json('em_contacts')->nullable();
            $table->integer('max_requests')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('general_confs');
    }
};
