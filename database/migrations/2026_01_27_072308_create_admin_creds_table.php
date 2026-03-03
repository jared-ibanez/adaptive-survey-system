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
        Schema::create('admin_creds', function (Blueprint $table) {
            // Primary key: serial
            $table->bigIncrements('seq_id');

            // Admin fields
            $table->text('first_name');
            $table->text('last_name');
            $table->text('password');

            // Role (e.g., 1 = super admin, 2 = admin)
            $table->integer('role');

            // Optional but recommended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_creds');
    }
};
