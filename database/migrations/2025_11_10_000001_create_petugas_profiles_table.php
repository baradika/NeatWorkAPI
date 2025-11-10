<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, create the table without foreign key
        Schema::create('petugas_profiles', function (Blueprint $table) {
            $table->id('id_petugas_profile');
            
            // Match the exact type of users.id_user
            $table->unsignedInteger('user_id');
            
            $table->string('ktp_number', 16);
            $table->string('ktp_photo_path');
            $table->string('selfie_with_ktp_path');
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('phone_number', 20);
            $table->text('address');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Add index
            $table->index('user_id');

            // Add foreign key constraint using Laravel's schema builder
            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        // Drop the table (foreign key will be dropped automatically)
        Schema::dropIfExists('petugas_profiles');
    }
};
