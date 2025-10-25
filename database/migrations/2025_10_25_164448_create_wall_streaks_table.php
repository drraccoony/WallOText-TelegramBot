<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wall_streaks', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();
            $table->timestamp('last_wall_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('wall_streaks');
    }
};
