<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('project_code'); // mã dự án
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('leader'); // trưởng dự án
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('status')->default('Đang làm');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
