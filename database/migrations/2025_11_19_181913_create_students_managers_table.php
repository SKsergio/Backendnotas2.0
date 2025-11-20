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
        Schema::create('students_managers', function (Blueprint $table) {
            $table->id();
            $table->string('DUI')->nullable()->unique();
            $table->string('passport')->nullable();
            $table->string('first_name', 20);
            $table->string('seccond_name', 20);
            $table->string('first_last_name', 20);
            $table->string('second_last_name', 20);
            $table->string('married_surname', 20)->nullable();
            $table->longText('direction');
            $table->date('birthdate')->nullable();
            $table->string('email');
            $table->integer('age')->nullable();
            // $table->string('email')->nullable(); aca va ir la nacionalidad cuando este el crud
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_managers');
    }
};
