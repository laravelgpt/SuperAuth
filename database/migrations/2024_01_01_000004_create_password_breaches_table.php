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
        Schema::create('password_breaches', function (Blueprint $table) {
            $table->id();
            $table->string('password_hash');
            $table->integer('breach_count')->default(0);
            $table->json('breach_details')->nullable();
            $table->timestamp('last_checked_at');
            $table->timestamps();
            
            $table->index('password_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_breaches');
    }
};
