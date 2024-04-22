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
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',250);
            $table->string('lastname',250);
            $table->decimal('salary',10,2);
            $table->date('dob')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('picture')->default('nopic.png');
            $table->index('firstname');
            /*
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreingId('user_id')->constrained('users');
            */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
