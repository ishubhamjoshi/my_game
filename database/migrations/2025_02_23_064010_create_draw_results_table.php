<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('draw_results', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('result', 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('draw_results');
    }
};
